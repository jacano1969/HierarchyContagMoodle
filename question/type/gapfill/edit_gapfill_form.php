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
 * The editing form code for this question type.
 * @package    qtype
 * @subpackage gapfill
 * @copyright  2012 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/question/type/edit_question_form.php');

defined('MOODLE_INTERNAL') || die();

/**
 * gapfill editing form definition.
 * 
 * See http://docs.moodle.org/en/Development:lib/formslib.php for information
 * about the Moodle forms library, which is based on the HTML Quickform PEAR library.
 */
class qtype_gapfill_edit_form extends question_edit_form {

    public $answer;
    public $answerdisplay;
    public $delimitchars;

    protected function definition_inner($mform) {
        $mform->addElement('hidden', 'reload', 1);
        $mform->removeelement('generalfeedback');

        // Make questiontext a required field for this question type.
        $mform->addRule('questiontext', null, 'required', null, 'client');

        // Default mark will be set to 1 * number of fields.
        $mform->removeelement('defaultmark');

                $mform->addElement('text', 'wronganswers', get_string('wronganswers', 'qtype_gapfill'), array('size' => 70));
        $mform->addHelpButton('wronganswers', 'wronganswers', 'qtype_gapfill');
        // The delimiting characters around fields.
        $delimitchars = array("[]" => "[ ]", "{}" => "{ }", "##" => "##", "@@" => "@ @");
        $mform->addElement('select', 'delimitchars', get_string('delimitchars', 'qtype_gapfill'), $delimitchars);
        $mform->addHelpButton('delimitchars', 'delimitchars', 'qtype_gapfill');

        $answer_display_types = array("dragdrop" => get_string('displaydragdrop', 'qtype_gapfill'),
            "gapfill" => get_string('displaygapfill', 'qtype_gapfill'),
            "dropdown" => get_string('displaydropdown', 'qtype_gapfill'));

        $mform->addElement('select', 'answerdisplay', get_string('answerdisplay', 'qtype_gapfill'), $answer_display_types);
        $mform->addHelpButton('answerdisplay', 'answerdisplay', 'qtype_gapfill');

        $mform->addElement('advcheckbox', 'casesensitive', get_string('casesensitive', 'qtype_gapfill'));

        $mform->addHelpButton('casesensitive', 'casesensitive', 'qtype_gapfill');

        $mform->addElement('advcheckbox', 'noduplicates', get_string('noduplicates', 'qtype_gapfill'));

        $mform->addHelpButton('noduplicates', 'noduplicates', 'qtype_gapfill');

        /* Only allow plain text in for the comma delimited set of wrong answer values
         * wrong answers really should be a set of zero marked ordinary answers in the answers
         * table.
         */
        $mform->setType('wronganswers', PARAM_TEXT);
        $mform->addElement('editor', 'generalfeedback', get_string('generalfeedback', 'question'),
                array('rows' => 10), $this->editoroptions);

        $mform->setType('generalfeedback', PARAM_RAW);
        $mform->addHelpButton('generalfeedback', 'generalfeedback', 'question');

        // To add combined feedback (correct, partial and incorrect).
        $this->add_combined_feedback_fields(true);

        // Adds hinting features.
        $this->add_interactive_settings();
    }

    public function set_data($question) {
        /* accessing the form in this way is probably not correct style */
        $this->_form->getElement('wronganswers')->setValue($this->get_wrong_answers($question));
        parent::set_data($question);
    }

    /**
     * Pull out a comma delimited string with the 
     * wrong answers in it from question->options->answers
     * @param type $question
     * @return type string
     */
    public function get_wrong_answers($question) {
        $wronganswers = "";
        if (property_exists($question, 'options')) {
            foreach ($question->options->answers as $a) {
                /* if it doesn't contain a 1 it must be zero */
                if (!(strpos($a->fraction, '1') !== false)) {
                    $wronganswers.=$a->answer . ",";
                }
            }
        }
        return $wronganswers = rtrim($wronganswers, ',');
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_combined_feedback($question);
        $question = $this->data_preprocessing_hints($question);

        if (!empty($question->options)) {
            $question->answerdisplay = $question->options->answerdisplay;
        }
        return $question;
    }

    public function validation($fromform, $data) {
        $errors = array();
        if ($errors) {
            return $errors;
        } else {
            return true;
        }
    }

    public function qtype() {
        return 'gapfill';
    }

}