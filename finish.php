<?php

/**
 * finish.php
 *
 * @package     mod_ctsr
 * @copyright   2022 Matt Donnelly
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_ctsr\ctsr_user;
use mod_ctsr\output\ctsr_finish;

require('../../config.php');
require_once($CFG->libdir.'/completionlib.php');

$cmid = optional_param('id', 0, PARAM_INT);

if (!$cm = get_coursemodule_from_id('ctsr', $cmid)) {
    print_error('invalidcoursemodule');
}
$ctsr = $DB->get_record('ctsr', array('id'=>$cm->instance), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
$isadmin = has_capability('mod/ctsr:manageinstance', $context);
$persistent = null;

if (!$isadmin) {
    $persistent = ctsr_user::get_record(['ctsr_id' => $ctsr->id, 'user_id' => $USER->id]);
    if (!$persistent || !$persistent->get('submitted')) {
        redirect(new moodle_url('/mod/ctsr/view.php', ['id' => $cmid]));
    }
    // Tell Moodle to check for completion
    $completion = new completion_info($course);
    if ($completion->is_enabled($cm) && $ctsr->completion_submission) {
        $completion->update_state($cm, COMPLETION_COMPLETE);
    }
}

// $PAGE setup
$PAGE->set_url('/mod/ctsr/finish.php', ['id' => $cmid]);
$PAGE->set_activity_record($ctsr);
$PAGE->set_title($COURSE->shortname. ': '. $ctsr->name);
$PAGE->set_heading($COURSE->fullname);

// Output
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($ctsr->name), 2);
echo $OUTPUT->render(new ctsr_finish($ctsr, $persistent));
echo $OUTPUT->footer();