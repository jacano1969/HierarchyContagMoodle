<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/* Hierarchy Tree library file */

/*
 * Section added by marigianna87@gmail.com (Computer Science Department, University of Crete)
 * Functions added for Hierarchy Tree feature
 */

require_once ('lib.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$WEB_SERVICE_URL = 'http://83.212.115.211:8080/HierarchyServices/rest/createinstances';

/**
 * Creates a new JSON file that will load an empty tree.
 * The root of the tree is the course's name
 *
 * @param $json_file_path - the file's path on server e.g. "var/www/moodle/blocks/contag/json"
 * @param $json_file_url - the file's absolute url e.g. "http://tsl7.csd.uoc.gr/marigianna/moodle/blocks/contag/edit_tags.php?id=2"
 * @param $coursename - the course's name
 */

function write_to_json_file($content, $json_file_path) {
	$fh = fopen($json_file_path, 'w') or die("can't open file");
	fwrite($fh, $content);
	fclose($fh);
}

/**
 * Forms moodle's root url to a url without special characters
 *
 * @param the url
 * @returns normalized string
 */
function normalize_url($url) {
	return preg_replace('/[^a-zA-Z0-9]/s', '', $url);

}

function copy_files($src, $dest) {
	$fsrc = fopen($src, 'r');
	$fdest = fopen($dest, 'w+');
	$len = stream_copy_to_stream($fsrc, $fdest);
	fclose($fsrc);
	fclose($fdest);
	return $len;
}

function get_json_file_path($normalized_url, $courseid) {
	$json_file_path = "/usr/share/tomcat7/lib/json/" . $normalized_url . '/' . $courseid . '/' . 'json_' . $courseid . '.json';
	return $json_file_path;
}

function get_json_path_local() {
	return getcwd() . '/json/';
}

/**
 * Imports new concept tags from json to db so that they will be displayed by default to the form that associated tags.
 * @params $json object as string
 * @params $courseid moodle course's id
 */
function import_json_to_db($json, $courseid) {
	global $DB;

	$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($json, TRUE)), RecursiveIteratorIterator::SELF_FIRST);

	foreach ($jsonIterator as $key => $val) {
		//gets the id first, and on next loop it gets the text
		//for each id
		if (strcmp($key, "id") == 0) {
			$tree_node_id = $val;
			continue;
		}
		//for each text
		if (strcmp($key, "text") == 0) {
			$cleaned_name = contag_clean_tag_input($val);
			if (contag_validate_tag_name_as_good($cleaned_name)) {
				//echo "$key => $val\n";
				$tag_id = contag_ensure_tag($courseid, $val, $tree_node_id, NULL);
				$item = $DB -> get_record('block_contag_tag', array('id' => $tag_id));
				//normalized_url is NULL as it is not needed
				if (!$tag_id) {
					echo "Could not save: " . $val . "\n";
				}
			} else {
				echo "Bad name on tag" . $cleaned_name;
			}
		}
	}
}

function json_file_get_max_id($json) {
	global $DB;

	$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($json, TRUE)), RecursiveIteratorIterator::SELF_FIRST);
	$max_id = -1;

	foreach ($jsonIterator as $key => $val) {
		//gets the id first, and on next loop it gets the text
		//for each id
		if (strcmp($key, "id") == 0) {
			if ($val >= $max_id) {
				$max_id = $val;
			}
		}
	}

	return $max_id;
}

function new_json_node($id, $text) {
	//create new content
	$content = new stdClass();
	$content -> id = $id;
	$content -> text = $text;
	return $content;
}

/*
 * Appends the new concept tag from association form to json
 * The tag is appended under the root. User needs to go and move the node on the desirable order
 * @param $concept_tag_object the object for insertion
 * @param $normalized url , the url without the special characters. Needed for finding json file and edit it
 * */
function append_node_to_json($contag_tag_object, $normalized_url) {
	$json_file_path = get_json_file_path($normalized_url, $contag_tag_object -> course_id);
	$json = file_get_contents($json_file_path);

	//compute new node id
	$tree_node_id = json_file_get_max_id($json) + 1;
	//gets max id PLUS 1 to make the next node
	$content = new_json_node($tree_node_id, $contag_tag_object -> tag_name);

	//insert new content to json
	$json_arr = json_decode($json);
	array_push($json_arr[0] -> children, $content);

	//write to file
	$content = json_encode($json_arr);
	write_to_json_file($content, $json_file_path);
	copy_from_server_to_local($contag_tag_object -> course_id);
	do_alert(get_string('concept_imported_to_tree', 'block_contag'));

	return $tree_node_id;
}

function do_alert($msg) {
	echo '<script type="text/javascript">alert("' . $msg . '"); </script>';
}

function create_to_delete_nodes_list($subtree_to_remove) {

	$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($subtree_to_remove, TRUE)), RecursiveIteratorIterator::SELF_FIRST);

	foreach ($jsonIterator as $key => $val) {
		//gets the id first, and on next loop it gets the text
		//for each id
		if (strcmp($key, "id") == 0) {
			$id = $val;
		}
		if (strcmp($key, "text") == 0) {
			$text = $val;
			$node = new_json_node($id, $text);
			if (!isset($_SESSION['to_remove_nodes'])) {
				$_SESSION['to_remove_nodes'] = array();
			}
			//an array of the ids to be removed
			array_push($_SESSION['to_remove_nodes'], $node);
		}
	}
}

/*
 * Returns nodes the list with tree_nodes_ids of nodes to delete
 * if we did not have any remove action, it returns null
 * */

function get_to_delete_nodes_list() {

	if (isset($_SESSION['to_remove_nodes'])) {
		return $_SESSION['to_remove_nodes'];
	} else {
		return NULL;
	}
}

/**
 * Deletes a node from the tree. This action includes:
 * nodes that will be removed from json automatically
 * delete assotiations on nodes and items
 * remove instances from RDF (TODO)
 * Unsets the to_remove_nodes session for further use
 *
 */
function contag_delete_tags_from_tree_node_id($to_remove_nodes, $courseid) {

	global $DB;

	foreach ($to_remove_nodes as $node) {
		//print_r($node);
		$tag_name = $node -> text;
		$tree_node_id = $node -> id;
		try {
			$item = $DB -> get_record('block_contag_tag', array('course_id' => $courseid, 'tag_name' => $tag_name, 'tree_node_id' => $tree_node_id));
			contag_delete_tag($item -> course_id, $item -> id);
		} catch (Exception $e) {
			echo 'Caught exception: ', $e -> getMessage(), "\n";
		}
	}
	unset($_SESSION['to_remove_nodes']);
	//job is done. unset the session for further use

}

function delete_nodes_to_remove_list() {
	unset($_SESSION['to_remove_nodes']);
}

function copy_from_server_to_local($courseid) {
	global $CFG;
	$normalized_url = normalize_url($CFG -> wwwroot);
	$json_file_path = get_json_file_path($normalized_url, $courseid);
	$json_path_local = get_json_path_local();

	if (!copy_files($json_file_path, $json_path_local . 'json_' . $courseid . '.json') != 0) {
		die("Error. Could not create JSON file locally. \n");
	}
}

/**
 * Checks if an array has dublicate values
 * @param the json as string
 * @return bollean
 */
function array_has_duplicates($json_array) {

	$text_array = array();

	$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($json_array, TRUE)), RecursiveIteratorIterator::SELF_FIRST);

	//creates an array with the text of the json object
	foreach ($jsonIterator as $key => $val) {
		if (strcmp($key, "text") == 0) {
			array_push($text_array, $val);
			continue;
		}
	}

	//checkes this array for duplicates

	return count($text_array) != count(array_unique($text_array));
}

/**
 *
 */

function create_rdf_instances($courseid, $normalized_url) {

	$json_str= create_instances_json();
	// $encode_parameters = $normalized_url . "/" . $courseid . "/" . $instances_json;
	// $call_web_service_url = $WEB_SERVICE_URL . "/" . $encode_parameters;
	// $resp = file_get_contents($call_web_service_url);
	// if (strcmp($resp, "true") == 0) {
	// echo "Instances created successfully";
	// } else {
	// echo "Oops. Something went wrong. Instances were not created. Please try again";
	// }
}

/**
 * Reads from database and creates a json that describes the relationships and contains the tree node id which is 
 * required information for the RDF database
 * @returns the json_str 
 */
function create_instances_json() {
	global $DB;

	$sql = "SELECT A.id as association_id,T.tree_node_id , A.item_id
			FROM mdl_block_contag_tag T
			INNER JOIN mdl_block_contag_association A
			ON T.id=A.tag_id
			ORDER BY association_id";
	
	//get result PHP object
	$result = $DB -> get_records_sql($sql); //records makes a sorting based on first column, in order to have unique appearances of an id
	//make array a json
	$json_str = json_encode($result);
	return $json_str ;

}
?>