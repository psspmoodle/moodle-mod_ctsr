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

    }
}