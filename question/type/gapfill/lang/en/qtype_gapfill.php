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
/**
 * The language strings for component 'qtype_gapfill', language 'en' 
 *    
 * @copyright &copy; 2012 Marcus Green
 * @author marcusavgreen@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package qtype
 * @subpackage gapfill
 */

$string['addinggapfill']='Adding Gap Fill';
$string['casesensitive']='Case Sensitive';
$string['casesensitive_help']='When this is checked, if the correct answer is CAT, cat will be flagged as a wrong answer';
$string['noduplicates']='No Duplicates';
$string['noduplicates_help']='When checked, each answer must be unique, useful where each field has a | operator, i.e. what are the colours of the Olympic medals and each field has [gold|silver|bronze], if the student enters gold in every field only the first will get a mark (the others will still get a tick though). It is really more like discard duplicate answers for marking purposes';


$string['delimitchars']='Delimit characters';
$string['pluginnameediting'] = 'Editing Gap Fill.';
$string['pluginnameadding'] = 'Adding a Gap Fill Question.';

$string['gapfill'] = 'Cloze Gapfill.';

$string['displaygapfill'] = 'gapfill';
$string['displaydropdown'] = 'dropdown';
$string['displaydragdrop'] = 'dragdrop';

$string['pluginname']="Gapfill question type";
$string['pluginname_help'] = 'Place the words to be completed within square brackets e.g. The [cat] sat on the [mat]. Dropdown and Dragdrop modes allows for a shuffled list of answers to be displayed which can include optional wrong/distractor answers.';

$string['pluginname_link']='/question/type/gapfill';
$string['pluginnamesummary'] = 'A fill in the gaps style question. Fewer features than the standard Cloze type, but simpler syntax';
$string['delimitchars_help']='Change the characters that delimit a field from the default [ ], useful for programming language questions';
$string['answerdisplay']='Display Answers';
$string['answerdisplay_help'] = 'If checked this will turn each field into a dropdown containing all answers for all fields';
$string['pleaseenterananswer']='Please enter an answer.';
$string['wronganswers']='Wrong answers.';
$string['wronganswers_help']='List of incorrect words separated by commas, only applies in dragdrop/dropdowns mode';


