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
 * Version details
 *
 * @package    block
 * @subpackage block_contag_dynamic_navigation
 * @copyright  2013 Marigianna Skouradaki
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');


//require_once('../../config.php');
require_once ($CFG -> dirroot . '/mod/quiz/lib.php');
require_once ($CFG -> dirroot . '/blocks/contag/lib.php');
require_once ($CFG -> dirroot . '/blocks/contag/hierarchy_tree_lib.php');


function get_tag_associations_from_quizid($courseid, $quizid) {
	global $DB;
	$sql = "SELECT A.id, A.tag_id, A.item_id, A.difficulty, Q.id, Q.course_id, Q.item_id
			FROM mdl_block_contag_association A
			INNER JOIN mdl_block_contag_item_quiz Q ON Q.id = A.item_id
			WHERE Q.item_id = " . $quizid . "
			ORDER BY A.item_id
			";

	//get result PHP object
	$result = $DB -> get_records_sql($sql);
	return ($result);
	//as array

}

function get_tags_links($courseid, $quiz_tags,$link,$params)
{
		
	$res = '<form method="post" action="'.  $_SERVER['PHP_SELF'].$params.'<select name="working_on_concept">  <option value="" selected="selected"></option> ';
    
	foreach ($quiz_tags as $tag)
	{
		$tag = contag_get_tag_from_tag_id($courseid, $tag -> tag_id);	  
       	$res .= '<option VALUE="'.$tag->id.'">'. $tag -> tag_name.'</option>';
	}
	$res .= '</select>
    <INPUT TYPE="submit" name="submit"/>
</form>';

return $res;
}

function get_tag_from_id($tag_id,$courseid)
{
		global $DB;

	//get result PHP object
		return $DB -> get_record('block_contag_tag', array('course_id' => $courseid, 'id' => $tag_id));

}

function get_module_id_from_tag_id($tag_id,$courseid)
{
		global $DB;

	//get result PHP object
		return $DB -> get_record('block_contag_item_quiz', array('course_id' => $courseid, 'id' => $tag_id));

}

function get_difficulty_link($difficulty,$tag,$courseid,$cm,$normalized_url,$difficulty_label)
{
	global $CFG;
	$resp_tags_ids = call_get_tags_of_difficulty_ws($difficulty,$tag,$courseid,$cm,$normalized_url);
	
	
	$resp_tags_ids = get_difficulty_tags_shuffled($resp_tags_ids,$courseid); 	//get all tags of this difficulty
	
	$cnt = 0;
	$res = '<br/><li>';
	$end_res = "";
	
	foreach ($resp_tags_ids as $new_tag_id)
	{

			$new_quiz =  get_module_id_from_tag_id($new_tag_id , $courseid);
			//error check : id does not correspond to quiz for some reason - go to next id 
			if(isset($new_quiz))
			{
				if($new_quiz -> item_id != $cm -> id)	//i do not need to link to the same quiz again
				{
			 		$link =  $CFG -> wwwroot.'/mod/quiz/view.php?id='.$new_quiz -> item_id ;
					$res .= '<a href="'.$link.'">';
					$end_res = '</a>' ;
					break;
				}
			}
	}
	$res .= $difficulty_label."on : ". $tag -> tag_name . $end_res. '</li>'; 	//make it look non-active when ids do not correspond
		
	return $res; 
}


function get_difficulty_tags_shuffled($resp_tags_ids,$courseid)
{
	$resp_tags_ids = json_decode($resp_tags_ids);
	shuffle($resp_tags_ids);
	return $resp_tags_ids;
}

function call_get_tags_of_difficulty_ws($difficulty,$tag,$courseid,$cm,$normalized_url)
{
	$web_service_url = 'http://83.212.123.121:8080/HierarchyServices/rest/gettagsfromdifficulty';
	
	$ws_item = new stdClass();
	
	
	$ws_item -> difficulty = $difficulty;
	$ws_item -> tag = $tag;
	//$ws_item -> cm = $cm;  //causes problems in json configuration but it is not needed anyway
	

	$ws_item_json = json_encode($ws_item);
	$json = urlencode($ws_item_json);
	
	$encode_parameters = $normalized_url . "/" . $courseid . "/" . $json;

	$call_web_service_url = $web_service_url . "/" . $encode_parameters;

	$resp_tags = file_get_contents($call_web_service_url);
	return $resp_tags;
}


?>