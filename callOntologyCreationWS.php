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

/**
 * Constants declaration
 */

require_once ('../../config.php');
require_once ('lib.php');


error_reporting(E_ALL);
ini_set('display_errors', '1');


//$WEB_SERVICE_URL = 'http://tsl7.csd.uoc.gr:8080/HierarchyServices/rest/createontology';

$WEB_SERVICE_URL = 'http://83.212.115.211:8080/HierarchyServices/rest/createontology';
/* ConTag Hierarchy Tree - Ontology creation
 * This is the file that calls the web service
 * with the corresponding JSON object
 * */

 
if (isset($_POST["json"]) && isset($_POST["courseId"])) {

	$url = urlencode($_POST["url"]);
	$courseid = urlencode($_POST["courseId"]);
	$json = urlencode($_POST["json"]);
	$encode_parameters = $url. "/" . $courseid . "/" . $json;

	$call_web_service_url = $WEB_SERVICE_URL . "/" . $encode_parameters;

	$resp = file_get_contents($call_web_service_url);

	if (strcmp($resp, "true") == 0) {
		echo "Concepts Hierarchy tree was saved successfully! \n";
		//ontology saved
		//if we have nodes to remove get the array
		$to_remove_nodes = get_to_delete_nodes_list();

		//delete these nodes from json file
		if(isset($to_remove_nodes))
		{
			contag_delete_tags_from_tree_node_id($to_remove_nodes,$courseid);
		}
		//delete_associations();
		//delete_items();
		
		//1. save files locally
		//need to copy file locally from server
		//in order to renew it its time.
//		$normalized_url = normalize_url($CFG -> wwwroot);
//		$json_file_path = get_json_file_path($normalized_url, $courseid);
//		$json_path_local = get_json_path_local();
//		if (!copy_files($json_file_path, $json_path_local . 'json_' . $courseid . '.json') != 0) {
//			die("Error. Could not create JSON file locally. \n");
//		}
		
		copy_from_server_to_local($courseid);
		//2.refresh db
		//read json file
		import_json_to_db($_POST["json"],$courseid);
		
		//return
		
		unset($_POST["url"]);
		unset($_POST["courseId"]);
		unset($_POST["json"]);

	} else {

		header('HTTP/1.1 500 Internal Server tsl7.cds.uoc.gr');
		header('Content-Type: application/json');
		die('Oops!! Something went wrong!');
	}
	
	

} else {
	echo "Malformed URL: course id , json object or url are missing.";
}
?>