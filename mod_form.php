<?php


/**
 * ctsr configuration form
 *
 * @package mod_ctsr
 * @copyright  2022 Matt Donnelly
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_ctsr\util;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_ctsr_mod_form extends moodleform_mod
{
    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    function definition() {
        global $CFG, $DB;

        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('name'), ['size'=>'48']);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $this->standard_intro_elements();

        $mform->addElement('textarea', 'finish_content', 'Finish page content', ['rows' => 12, 'cols' => 100]);
        $mform->setType('finish_content', PARAM_RAW);

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();

    }

        /**
         * Add elements for setting the custom completion rules.
         *
         * @return array List of added element names, or names of wrapping group elements.
         * @category completion
         */
        public function add_completion_rules(): array
        {
            $mform =& $this->_form;
            $mform->addElement('advcheckbox', 'completion_submission', util::s('completion_submissiongroup'), util::s('completion_submission'));
            return ['completion_submission'];
        }

        /**
         * Called during validation to see whether some module-specific completion rules are selected.
         *
         * @param array $data Input data not yet validated.
         * @return bool True if one or more rules is enabled, false if none are.
         */
        public function completion_rule_enabled($data): bool {
            return !empty($data['completion_submission']);
        }
}