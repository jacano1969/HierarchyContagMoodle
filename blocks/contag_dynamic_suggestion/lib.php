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

function get_suggestion_quiz_statistics($userid, $cm) {
	global $DB;

	$sql = "SELECT count( A.id ) AS attempts, G.grade AS grade
FROM mdl_quiz_grades G, mdl_quiz_attempts A
WHERE A.userid =" . $userid . "
AND G.userid =" . $userid . "
AND G.quiz =" . $cm -> instance . "
AND A.quiz =" . $cm -> instance . "
AND A.state = 'finished'";
	$result = $DB -> get_records_sql($sql);
	return ($result);
}

function get_suggestion_concept_statistics($working_tag_id, $userid) {
	global $DB;

	$sql = "SELECT COUNT( qattempts.attempt ) AS 'attempts', AVG( grades.grade ) AS 'grade'
FROM mdl_quiz quiz
INNER JOIN mdl_quiz_attempts qattempts ON quiz.id = qattempts.quiz
INNER JOIN mdl_course_modules cource ON quiz.id = cource.instance
INNER JOIN mdl_block_contag_item_quiz ciquiz ON cource.id = ciquiz.item_id
INNER JOIN mdl_block_contag_association association ON ciquiz.id = association.item_id
INNER JOIN mdl_quiz_grades grades ON grades.quiz = quiz.id
WHERE association.tag_id =" . $working_tag_id . "
AND qattempts.state = 'finished'
AND qattempts.userid =" . $userid;
	$result = $DB -> get_records_sql($sql);
	return ($result);
}

function get_module_link($type, $courseid, $id) {
	global $DB, $CFG;
	$item = $DB -> get_record('block_contag_item_' . $type, array('course_id' => $courseid, 'id' => $id));

	$link = $CFG -> wwwroot . '/mod/' . $type . '/view.php?id=' . $item -> item_id;

	$course_module = $DB -> get_record('course_modules', array('id' => $item -> item_id));
	$real_item = $DB -> get_record($type, array('id' => $course_module -> instance));

	$res = '<p><a href="' . $link . '">' . $real_item -> name . '</a></p>';

	return $res;
}

function get_forum_suggestion($resp_data, $courseid) {
	if (property_exists($resp_data, "forum")) {
		$res = '<li>';

		$forum = $resp_data -> forum;
		$res .= $forum -> text . " ";
		$cnt = 0;
		foreach ($forum -> data as $forumid) {
			$res .= get_module_link("forum", $courseid, $forumid);
			$cnt++;
			if ($cnt == 3) {
				break;
			}
		}
		//fetch at most 3 values of forums and give links
		$res .= '</li>';
		return $res;

	}
}

function get_page_suggestion($resp_data, $courseid) {
	if (property_exists($resp_data, "theory")) {
		$res = '<li>';

		$theory = $resp_data -> theory;
		$res .= $theory -> text . " ";
		$cnt = 0;
		foreach ($theory -> data as $pageid) {
			$res .= get_module_link("page", $courseid, $pageid);
			$cnt++;
			if ($cnt == 3) {
				break;
			}
		}
		//fetch at most 3 values of forums and give links
		$res .= '</li>';
		return $res;

	}
}

function call_suggestion_rules($courseid, $normalized_url, $userid, $cm, $working_tag_id, $json_obj) {
	global $USER, $CFG, $DB;
	$tag = get_tag_from_id($working_tag_id, $courseid);
	$json = urlencode($json_obj);

	$web_service_url = 'http://83.212.123.121:8080/HierarchyServices/rest/adaptivesuggestions';

	$encode_parameters = $normalized_url . "/" . $courseid . "/" . $USER -> id . "/" . $json;

	$call_web_service_url = $web_service_url . "/" . $encode_parameters;
	$resp_data = file_get_contents($call_web_service_url);
	$resp_data = json_decode($resp_data);

	$res = urldecode($resp_data -> msg);
	
	$res .= '<br/><ul>';

	//I got the object now for each field
	if ($resp_data -> result == 0) {
		$res .= '<img src="'.$CFG -> wwwroot."/blocks/contag_dynamic_suggestion/images/try_again".rand(1, 3).".gif"
		.'"alt="Try Again..." width="60px" height="80px" style="float: right;" >';

		$res .= get_page_suggestion($resp_data, $courseid);
		//help peers on forum
		$res .= get_forum_suggestion($resp_data, $courseid);
		$res .= '<li>';
		$res .= $resp_data -> practice;
		$res .= '</li>';

			
	} else {
		$res .= '<img src="'.$CFG -> wwwroot."/blocks/contag_dynamic_suggestion/images/bravo".rand(1, 3)
		.".gif".'"alt="Bravo!" width="60px" height="60px" style="float: right;" >';
			
		//search on the web
		$res .= '<li>';

		$web = $resp_data -> web;
		$res .= $web -> text . " " . $tag -> tag_name;
		$res .= '</li>';
		$res .= get_forum_suggestion($resp_data, $courseid);
		//help peers on forum

		$res .= '<li>';
		$res .= $resp_data -> practice;
		$res .= '</li>';
		
	}
	$res .= "</ul>";
	return $res;
}


?>