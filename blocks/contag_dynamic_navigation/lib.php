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
require_once($CFG->dirroot . '/mod/quiz/lib.php');
require_once($CFG->dirroot . '/blocks/contag/lib.php');


function get_tag_associations_from_quizid($courseid, $quizid)
{
	global $DB;
	$sql = "SELECT A.id, A.tag_id, A.item_id, A.difficulty, Q.id, Q.course_id, Q.item_id
FROM mdl_block_contag_association A
INNER JOIN mdl_block_contag_item_quiz Q ON Q.id = A.item_id
WHERE Q.item_id = ". $quizid."
ORDER BY A.item_id
";

	//get result PHP object
	$result = $DB -> get_records_sql($sql);
	return ($result);	//as array
	/*
	 * 
	 * 
	 * */
}

?>