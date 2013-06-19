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
 * Form for editing Block ConTag Suggestion block instances.
 *
 * @copyright 2012 Marigianna Skouradaki
 */

class block_contag_dynamic_suggestion_edit_form extends block_edit_form {
	protected function specific_definition($mform) {
			
			
			
		$mform -> addElement('header', 'configheader', get_string('adaptivity_settings', 'block_contag_dynamic_suggestion'));

		$mform->addElement('advcheckbox', 'config_quizconcept', get_string('concept','block_contag_dynamic_suggestion'),  get_string('focus','block_contag_dynamic_suggestion'), array('group' => 1), array(0, 1));
		
		// A sample string variable with a default value.
		$mform -> addElement('text', 'config_low_achievement_msg', get_string('low_achievement_msg', 'block_contag_dynamic_suggestion'));
		$mform -> addElement('text', 'config_high_achievement_msg', get_string('high_achievement_msg', 'block_contag_dynamic_suggestion'));
		$mform -> addElement('text', 'config_minimum_attempts', get_string('minimum_attempts', 'block_contag_dynamic_suggestion'));
		$mform -> addElement('text', 'config_lowest_grade', get_string('lowest_grade', 'block_contag_dynamic_suggestion'));
		
		$mform -> setDefault('config_low_achievement_msg', get_string('low_achievement_msg_const', 'block_contag_dynamic_suggestion'));
		$mform -> setDefault('config_high_achievement_msg', get_string('high_achievement_msg_const', 'block_contag_dynamic_suggestion'));
		$mform -> setDefault('config_minimum_attempts', get_string('minimum_attempts_const', 'block_contag_dynamic_suggestion'));
		$mform -> setDefault('config_lowest_grade', get_string('lowest_grade_const', 'block_contag_dynamic_suggestion'));
		$mform->setDefault('quizconcept', 0);
		
		$mform -> setType('config_low_achievement_msg', PARAM_TEXT);
		$mform -> setType('config_high_achievement_msg', PARAM_TEXT);
		$mform -> setType('config_minimum_attempts', PARAM_INT);
		$mform -> setType('config_lowest_grade', PARAM_INT);



}

}
