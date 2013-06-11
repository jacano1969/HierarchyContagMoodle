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

/**
 * TODO: change that to work for any type*/
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

function get_tags_links($courseid, $quiz_tags, $link, $params) {

	$res = '<form method="post" action="' . $_SERVER['PHP_SELF'] . $params . '<select name="working_on_concept">';

	foreach ($quiz_tags as $tag) {
		$tag = contag_get_tag_from_tag_id($courseid, $tag -> tag_id);
		$res .= '<option VALUE="' . $tag -> id . '">' . $tag -> tag_name . '</option>';
	}
	$res .= '</select><button type="submit">' . get_string('submit_button', 'block_contag_dynamic_navigation') . '</button></form>';

	return $res;
}

function get_tag_from_id($tag_id, $courseid) {
	global $DB;

	//get result PHP object
	return $DB -> get_record('block_contag_tag', array('course_id' => $courseid, 'id' => $tag_id));

}

function get_module_id_from_tag_id($tag_id, $courseid) {
	global $DB;

	//get result PHP object
	return $DB -> get_record('block_contag_item_quiz', array('course_id' => $courseid, 'id' => $tag_id));

}

/*
 * TODO:
 * might need modifying for fittng to all dynamically created links
 * might need modifying to work for any type
 * */

function get_difficulty_link($difficulty, $tag, $courseid, $cm, $normalized_url, $difficulty_label) {
	global $CFG;
	$resp_tags_ids = call_get_tags_of_difficulty_ws($difficulty, $tag, $courseid, $cm, $normalized_url);

	$resp_tags_ids = shuffle_array($resp_tags_ids);
	//get all tags of this difficulty

	$cnt = 0;
	$res = '<br/><li>';
	$end_res = "";

	foreach ($resp_tags_ids as $new_tag_id) {

		$new_quiz = get_module_id_from_tag_id($new_tag_id, $courseid);
		//error check : id does not correspond to quiz for some reason - go to next id
		if (isset($new_quiz)) {
			if ($new_quiz -> item_id != $cm -> id)//i do not need to link to the same quiz again
			{
				$link = $CFG -> wwwroot . '/mod/quiz/view.php?id=' . $new_quiz -> item_id;
				$res .= '<a href="' . $link . '">';
				$end_res = '</a>';
				break;
			}
		}
	}
	$res .= $difficulty_label . "on : " . $tag -> tag_name . $end_res . '</li>';
	//make it look non-active when ids do not correspond

	return $res;
}

function shuffle_array($array) {
	$array = json_decode($array);
	shuffle($array);
	return $array;
}

function call_get_tags_of_difficulty_ws($difficulty, $tag, $courseid, $cm, $normalized_url) {
	$web_service_url = 'http://83.212.123.121:8080/HierarchyServices/rest/gettagsfromdifficulty';

	$ws_item = new stdClass();

	$ws_item -> difficulty = $difficulty;
	$ws_item -> tag = $tag;
	//$ws_item -> cm = $cm;  //causes problems in json configuration but it is not needed anyway

	$ws_item_json = json_encode($ws_item);
	//print_r($ws_item_json);
	//print_r($ws_item_json);
	$json = urlencode($ws_item_json);

	$encode_parameters = $normalized_url . "/" . $courseid . "/" . $json;

	$call_web_service_url = $web_service_url . "/" . $encode_parameters;

	$resp_tags = file_get_contents($call_web_service_url);
	return $resp_tags;
}

function call_navigation_rules($courseid, $normalized_url, $userid, $cm, $working_tag_id) {
	global $USER, $CFG, $DB;
	$tag = get_tag_from_id($working_tag_id, $courseid);
	$statistics_arr = get_statistics_arr($courseid, $userid, $cm, $working_tag_id);
	$statistics_obj = get_statistics_obj($tag -> tree_node_id, $statistics_arr);
	//print_r($statistics_obj);
	$json = urlencode($statistics_obj);

	$web_service_url = 'http://83.212.123.121:8080/HierarchyServices/rest/triggerrules';

	$encode_parameters = $normalized_url . "/" . $courseid . "/" . $USER -> id . "/" . $json;

	$call_web_service_url = $web_service_url . "/" . $encode_parameters;

	$resp_tags = file_get_contents($call_web_service_url);
	$resp_tags = json_decode($resp_tags);
	//print_r($resp_tags);
	$res = "";

	if (!empty($resp_tags)) {
	//	$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($resp_tags), TRUE), RecursiveIteratorIterator::SELF_FIRST);

		//will go in if any new unlocked categories
		foreach ($resp_tags as $key => $value) {
			shuffle($value);
			var_dump($value);
			$cnt = 0;
			$res = '<br/>';
			$end_res = "";
			$res .= get_string('new_concept_unlocked', 'block_contag_dynamic_navigation');
			foreach ($value as $quiz) {

				$random_quiz = get_module_id_from_tag_id($quiz, $courseid);
				//error check : id does not correspond to quiz for some reason - go to next id
				if (isset($random_quiz)) {
					if ($random_quiz -> item_id != $cm -> id)//i do not need to link to the same quiz again
					{
						$link = $CFG -> wwwroot . '/mod/quiz/view.php?id=' . $random_quiz -> item_id;
						$res .= '<a href="' . $link . '">';
						$end_res = '</a>';
						$tree_node_id = intval($key);
						$tag = $DB -> get_record("block_contag_tag", array("tree_node_id" => $tree_node_id));
						$res .= $tag -> tag_name . $end_res ;
						
						//open new category to user
						$group_member = get_new_member($USER -> id, $tree_node_id, $courseid);
						$member = $DB -> insert_record('groups_members', $group_member);
						break;
					}
				}
				

			}

		}
	}
	return $res;
}

function get_statistics_arr($courseid, $userid, $cm, $working_tag_id) {
	global $DB;
	
	for ($i = 1; $i < 4; $i++) {
			$result -> difficulty = $i;
			$result -> attempts = "0";
			$result -> grade = "0.0";
	}
	$sql = "
 SELECT
  association.difficulty AS 'difficulty', COUNT(qattempts.attempt) AS 'attempts'
  , AVG(grades.grade)  AS 'grade'
  FROM mdl_quiz quiz
  INNER JOIN mdl_quiz_attempts    qattempts  ON quiz.id = qattempts.quiz
  INNER JOIN mdl_course_modules    cource   ON quiz.id = cource.instance
  INNER JOIN mdl_block_contag_item_quiz  ciquiz   ON cource.id = ciquiz.item_id
  INNER JOIN mdl_block_contag_association association ON ciquiz.id = association.item_id
  INNER JOIN mdl_quiz_grades grades ON grades.quiz = quiz.id
  WHERE
  association.tag_id = " . $working_tag_id . " AND qattempts.state = 'finished' 
  AND qattempts.userid = " . $userid . " AND association.difficulty 
  AND association.item_type = 'quiz'
  GROUP BY association.difficulty";
	//get result PHP object
	$result = $DB -> get_records_sql($sql);
	//print_r($result);
	return ($result);

}

function get_constants_obj($lowest_grade, $minimum_attempts) {
	$obj = new stdClass();
	$obj -> lowest_grade = $lowest_grade;
	$obj -> minimum_attempts = $minimum_attempts;
	return $obj;
}

function get_statistics_obj($tree_node_id, $statistics_arr) {

	$obj = new stdClass();
	$obj -> tag_id = $tree_node_id;

	$obj -> statistics = $statistics_arr;


	/*
	 * TODO: change according to teacher's editing*/
	$obj -> constants[CONTAG_DIFFICULTY_EASY] = get_constants_obj("20.0", "1");
	$obj -> constants[CONTAG_DIFFICULTY_MEDIUM] = get_constants_obj("30.0", "1");
	$obj -> constants[CONTAG_DIFFICULTY_HARD] = get_constants_obj("50.0", "1");
	return json_encode($obj);
}

function change_visibility_of_modules($cm, $moduleid, $visibility) {
	global $DB;

	$course = $DB -> get_record('course', array('id' => $cm -> course), '*', MUST_EXIST);

	require_login($course, false, $cm);
	//$coursecontext = get_context_instance(CONTEXT_COURSE, $course -> id);
	$modcontext = get_context_instance(CONTEXT_MODULE, $moduleid);
	//require_capability('moodle/course:activityvisibility', $modcontext);

	set_coursemodule_visible($moduleid, $visibility);

	rebuild_course_cache($cm -> course);
}
?>