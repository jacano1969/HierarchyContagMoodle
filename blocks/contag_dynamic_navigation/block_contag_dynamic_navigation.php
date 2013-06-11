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

define("CONTAG_DIFFICULTY_EASY", 1);
define("CONTAG_DIFFICULTY_MEDIUM", 2);
define("CONTAG_DIFFICULTY_HARD", 3);

//require_once('../../config.php');
require_once (dirname(__FILE__) . "/lib.php");
require_once($CFG->dirroot.'/course/lib.php');

// with just lib.php, this was failing depending on where the block was being seen!

defined('MOODLE_INTERNAL') || die();


class block_contag_dynamic_navigation extends block_base {

	
	public function init() {
			
		$this -> title = get_string('contag_dynamic_navigation', 'block_contag_dynamic_navigation');
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
		$link = $CFG -> wwwroot . '/blocks/contag_dynamic_navigation/';
		/*test code*/
		/* when i am a student it tries to hide activities while i do not have capability */
		//if it works make a summary of that
		
		/*test code -- end*/
		//if the student has not chosen a working concept category
		if (!isset($_POST['working_on_concept'])) {

			if (isset($quiz_tags) && !empty($quiz_tags)) {
				
				if (isset($_GET['attempt'])) {
					$res = get_string('quiz_associated_tags', 'block_contag_dynamic_navigation');
					$params = '?attempt=' . $_GET['attempt'] . '">';
					$res .= get_tags_links($courseid, $quiz_tags, $link, $params);
				} 
else
{
	$res = get_string('block_available_on_attempt', 'block_contag_dynamic_navigation');
}

			} else {
				$res = get_string('not_associated_with_tags', 'block_contag_dynamic_navigation');
			}

		} else//student has chosen a working concept
		{
				
			$normalized_url = normalize_url($CFG -> wwwroot);
			
			$working_tag_id = $_POST['working_on_concept'];
			
			$tag = get_tag_from_id($working_tag_id, $courseid);
			$res = '<ul>';
			$res .= get_difficulty_link(CONTAG_DIFFICULTY_EASY,$tag,$courseid,$cm,$normalized_url,get_string('easy_concept_quiz', 'block_contag_dynamic_navigation'));  //1 for easy
			$res .= get_difficulty_link(CONTAG_DIFFICULTY_MEDIUM,$tag,$courseid,$cm,$normalized_url,get_string('medium_concept_quiz', 'block_contag_dynamic_navigation'));  //2 for medium
			$res .= get_difficulty_link(CONTAG_DIFFICULTY_HARD,$tag,$courseid,$cm,$normalized_url,get_string('hard_concept_quiz', 'block_contag_dynamic_navigation'));  //3 for hard
		
			$tmp =call_navigation_rules($courseid, $normalized_url, $userid, $cm, $working_tag_id);
			
			$res .= '</ul>';

		}

		$this -> content -> text = $res;
		// END BLOCK MAIN LINKS

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
