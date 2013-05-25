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
class block_contag_dynamic_navigation extends block_base {
	public function init() {
		$this -> title = get_string('contag_dynamic_navigation', 'block_contag_dynamic_navigation');
	}

	public function get_content() {
		
		global $CFG, $COURSE;
        $courseid = $COURSE->id;
        $context = get_context_instance(CONTEXT_COURSE, $courseid);
		if ($this -> content !== null) {
			return $this -> content;
		}

		$this -> content = new stdClass;
		$this -> content -> footer = '';

	  
            // START BLOCK MAIN LINKS
            //here we construct the footer through the service
            
            $res = '<ul>';
            $link = $CFG->wwwroot.'/blocks/contag_dynamic_navigation/';
            
            // display "Navigate" link
            //$res .= '<li>'.'<a href="'.$link.'view.php?id='.$courseid.'">'.get_string('navigate_by_concept_tags', 'block_contag').'</a>'.'</li>'; // TODO: Is it safe to pass in the courseid like this?
            
            
          	$res .= '<br/><li>' . '<a href="#">' . get_string('easier_concept_quiz', 'block_contag_dynamic_navigation') . '</a>' . '</li>';
			$res .= '<br/><li>' . '<a href="#">' . get_string('similar_concept_quiz', 'block_contag_dynamic_navigation') . '</a>' . '</li>';
			$res .= '<br/><li>' . '<a href="#">' . get_string('harder_concept_quiz', 'block_contag_dynamic_navigation') . '</a>' . '</li>';

            
            $this->content->text = $res.'</ul>';
            // END BLOCK MAIN LINKS
	
		return $this -> content;
	}

	/**
	 * Set the applicable formats for this block to all
	 * @return array
	 */
	function applicable_formats() {
		return array('mod-quiz' => true); //appears only on quizes
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
