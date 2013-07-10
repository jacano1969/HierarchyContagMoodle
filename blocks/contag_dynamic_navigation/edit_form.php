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
 * Form for editing Block ConTag Dynamic Navigation block instances.
 *
 * @copyright 2011 Evgeny Bogdanov
 */

class block_contag_dynamic_navigation_edit_form extends block_edit_form {
	protected function specific_definition($mform) {
		$mform -> addElement('header', 'configheader', get_string('blocksettings', 'block'));

		// A sample string variable with a default value.
		$mform -> addElement('text', 'config_minimum_attempts_easy', get_string('minimum_attempts_easy', 'block_contag_dynamic_navigation'));
		$mform -> addElement('text', 'config_lowest_avg_grade_easy', get_string('lowest_avg_grade_easy', 'block_contag_dynamic_navigation'));
		$mform -> addElement('text', 'config_minimum_attempts_medium', get_string('minimum_attempts_medium', 'block_contag_dynamic_navigation'));
		$mform -> addElement('text', 'config_lowest_avg_grade_medium', get_string('lowest_avg_grade_medium', 'block_contag_dynamic_navigation'));
		$mform -> addElement('text', 'config_minimum_attempts_hard', get_string('minimum_attempts_hard', 'block_contag_dynamic_navigation'));
		$mform -> addElement('text', 'config_lowest_avg_grade_hard', get_string('lowest_avg_grade_hard', 'block_contag_dynamic_navigation'));

		$mform -> setDefault('config_minimum_attempts_easy', 5);
		$mform -> setDefault('config_lowest_avg_grade_easy',80);
		$mform -> setDefault('config_minimum_attempts_medium', 4);
		$mform -> setDefault('config_lowest_avg_grade_medium', 70);
		$mform -> setDefault('config_minimum_attempts_hard', 3);
		$mform -> setDefault('config_lowest_avg_grade_hard', 60);

		$mform -> setType('config_minimum_attempts_easy', PARAM_INT );
		$mform -> setType('config_lowest_avg_grade_easy', PARAM_INT );
		$mform -> setType('config_minimum_attempts_medium', PARAM_INT );
		$mform -> setType('config_lowest_avg_grade_medium',PARAM_INT );
		$mform -> setType('config_minimum_attempts_hard',PARAM_INT );
		$mform -> setType('config_lowest_avg_grade_hard', PARAM_INT );

	}

}
