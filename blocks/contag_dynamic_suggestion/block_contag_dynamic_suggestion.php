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

error_reporting(E_ALL);
ini_set('display_errors', '1');

define("LOWEST_GRADE", "70.0");
define("MINIMUM_ATTEMPTS", "1");

define("LOW_ACHIEVEMENT_MSG", "Hmm....I am sure you could do better!");
define("HIGH_ACHIEVEMENT_MSG", "Cograts! You did very well!");

//require_once('../../config.php');
require_once (dirname(__FILE__) . "/lib.php");
require_once ($CFG -> dirroot . '/course/lib.php');

// with just lib.php, this was failing depending on where the block was being seen!

defined('MOODLE_INTERNAL') || die();

class block_contag_dynamic_suggestion extends block_base {

	public function init() {

		$this -> title = get_string('contag_dynamic_suggestion', 'block_contag_dynamic_suggestion');
	
	}

	/**
	 * If this block belongs to a quiz context, then return that quiz's id.
	 * Otherwise, return 0.
	 * @return integer the quiz id.
	 */
	function get_cm() {
		if (empty($this -> instance -> parentcontextid)) {
			return 0;
		}
		$parentcontext = get_context_instance_by_id($this -> instance -> parentcontextid);
		//print_r($parentcontext);
		if ($parentcontext -> contextlevel != CONTEXT_MODULE) {
			return 0;
		}
		$cm = get_coursemodule_from_id('quiz', $parentcontext -> instanceid);
		if (!$cm) {
			return 0;
		}
		return $cm;
	}

	public function get_content() {

		global $CFG, $COURSE, $USER, $DB;

		$courseid = $COURSE -> id;
		$userid = $USER -> id;

		$context = get_context_instance(CONTEXT_COURSE, $courseid);
		if ($this -> content !== null) {
			return $this -> content;
		}

		$this -> content = new stdClass;
		$this -> content -> footer = '';

		$cm = $this -> get_cm();

		$quiz_tags = get_tag_associations_from_quizid($courseid, $cm -> id);
		$link = $CFG -> wwwroot . '/blocks/contag_dynamic_suggestion/';
		/*test code*/
		/* when i am a student it tries to hide activities while i do not have capability */
		//if it works make a summary of that

		/*test code -- end*/
		//if the student has not chosen a working concept category
		if (!isset($_POST['working_on_concept'])) {

			$this -> content -> text = get_string('select_working_tag', 'block_contag_dynamic_suggestion');
		} else//student has chosen a working concept
		{

			$normalized_url = normalize_url($CFG -> wwwroot);

			$working_tag_id = $_POST['working_on_concept'];
			$tag = get_tag_from_id($working_tag_id, $courseid);

			$conctants_arr = new stdClass();
			$statistics_arr = new stdClass();
			$json_obj = new stdClass();
			$json_obj -> tag_id = $tag -> tree_node_id;
			$json_obj -> statistics = new ArrayObject();
			$json_obj -> constants = new ArrayObject();
			if (strcmp($this -> config -> quizconcept, "0") == 0) {
				$sql_end = " AND quiz.id =" . $cm -> instance;
				$statistics_arr = get_suggestion_quiz_statistics($userid, $cm);
			} else {
				$statistics_arr = get_suggestion_concept_statistics($working_tag_id, $userid);
			}

			foreach ($statistics_arr as $value) {
				$json_obj -> statistics[0] = $value;
			}
			if (!empty($this -> config -> minimum_attempts)) {
				$conctants_arr -> minimum_attempts = strval($this -> config -> minimum_attempts);

			} else {
				$conctants_arr -> minimum_attempts = MINIMUM_ATTEMPTS;
			}
			if (!empty($this -> config -> lowest_grade)) {
				$conctants_arr -> lowest_grade = strval($this -> config -> lowest_grade);

			} else {
				$conctants_arr -> lowest_grade = LOWEST_GRADE;
			}
			if (!empty($this -> config -> low_achievement_msg)) {
				$conctants_arr -> low_achievement_msg = strval($this -> config -> low_achievement_msg);

			} else {
				$conctants_arr -> low_achievement_msg = LOW_ACHIEVEMENT_MSG;
			}
			if (!empty($this -> config -> high_achievement_msg)) {
				$conctants_arr -> high_achievement_msg = strval($this -> config -> high_achievement_msg);

			} else {
				$conctants_arr -> minimum_attempts = HIGH_ACHIEVEMENT_MSG;
			}
			$json_obj -> constants[0] = $conctants_arr;

			$this -> content -> text .= call_suggestion_rules($courseid, $normalized_url, $userid, $cm, $working_tag_id, json_encode($json_obj));

		}
			
		return $this -> content;
	}

	/**
	 * Set the applicable formats for this block to all
	 * @return array
	 */
	function applicable_formats() {
		return array('mod-quiz' => true);
		//appears only on quizes
		//return array('mod-*' => true);
	}

	/**
	 * All multiple instances of this block
	 * @return bool Returns false
	 */
	function instance_allow_multiple() {
		return false;
	}

} // Here's the closing bracket for the class definition
?>
