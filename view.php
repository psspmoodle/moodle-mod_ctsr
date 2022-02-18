<?php

/**
 * view.php
 *
 * @package     mod_ctsr
 * @copyright   2022 Matt Donnelly
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require('../../config.php');
require_once($CFG->dirroot.'/mod/ctsr/lib.php');
require_once($CFG->libdir.'/completionlib.php');

$cmid = optional_param('id', 0, PARAM_INT); // Course Module ID

if (!$cm = get_coursemodule_from_id('ctsr', $cmid)) {
    print_error('invalidcoursemodule');
}
$ctsr = $DB->get_record('ctsr', array('id'=>$cm->instance), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
//$context = context_module::instance($cm->id);
//require_capability('mod/ctsr:view', $context);

// Update Moodle completion
$completion = new completion_info($COURSE);
$completion->set_module_viewed($cm);




// $PAGE setup
$PAGE->set_url('/mod/ctsr/view.php', ['id' => $cmid]);
$PAGE->set_title($COURSE->shortname. ': '. $ctsr->name);
$PAGE->set_heading($COURSE->fullname);
// We don't want users starting, hitting the back button, and seeing (and clicking) another start button
$PAGE->set_activity_record($ctsr);

// Output
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($ctsr->name), 2);
echo $OUTPUT->box_start('mod_introbox', 'ctsrintro');
echo $OUTPUT->render(new ctsr_view($cmid, $ctsr));
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
