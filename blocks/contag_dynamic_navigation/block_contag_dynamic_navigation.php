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

//require_once('../../config.php');
require_once (dirname(__FILE__) . "/lib.php");
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

		global $CFG, $COURSE;

		$courseid = $COURSE -> id;

		$context = get_context_instance(CONTEXT_COURSE, $courseid);

		if ($this -> content !== null) {
			return $this -> content;
		}

		$this -> content = new stdClass;
		$this -> content -> footer = '';
		
		
		$cm = $this -> get_cm();

		$quiz_tags = get_tag_associations_from_quizid($courseid, $cm -> id);
		
		if(isset($quiz_tags))
		{
			echo "quiz has tags: ";
			print_r($quiz_tags);
		}
		else
		{
				echo "quiz not associated to any tags";
		}
		
		$res = '<ul>';
		//$link = $CFG -> wwwroot . '/blocks/contag_dynamic_navigation/';

		// display "Navigate" link
		//$res .= '<li>'.'<a href="'.$link.'view.php?id='.$courseid.'">'.get_string('navigate_by_concept_tags', 'block_contag').'</a>'.'</li>'; // TODO: Is it safe to pass in the courseid like this?

		$res .= '<br/><li>' . '<a href="www.google.com">' . get_string('easier_concept_quiz', 'block_contag_dynamic_navigation') . '</a>' . '</li>';
		$res .= '<br/><li>' . '<a href="#">' . get_string('similar_concept_quiz', 'block_contag_dynamic_navigation') . '</a>' . '</li>';
		$res .= '<br/><li>' . '<a href="#">' . get_string('harder_concept_quiz', 'block_contag_dynamic_navigation') . '</a>' . '</li>';

		$this -> content -> text = $res . '</ul>';
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
