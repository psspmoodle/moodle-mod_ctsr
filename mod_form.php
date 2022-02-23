<?php


/**
 * ctsr configuration form
 *
 * @package mod_ctsr
 * @copyright  2022 Matt Donnelly
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_ctsr\persistent\frame;
use mod_ctsr\util\util;
use mod_ctsr\util\validator;

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

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();

    }
}