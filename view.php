<?php

/**
 * view.php
 *
 * @package     mod_ctsr
 * @copyright   2022 Matt Donnelly
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_ctsr\ctsr;
use mod_ctsr\ctsr_form as form;
use mod_ctsr\output\ctsr_view;

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

$persistent = ctsr::get_record(['ctsr_id' => $ctsr->id, 'user_id' => $USER->id]);
$customdata = [
    'persistent' => $persistent,    // An instance, or null
    'ctsr_id' => $ctsr->id,
    'user_id' => $USER->id
];



// $PAGE setup
$PAGE->set_url('/mod/ctsr/view.php', ['id' => $cmid]);
$PAGE->set_title($COURSE->shortname. ': '. $ctsr->name);
$PAGE->set_heading($COURSE->fullname);
$PAGE->requires->js_call_amd('mod_ctsr/enable_tooltips', 'init');
$PAGE->requires->js_call_amd('mod_ctsr/store_tab', 'init');
$PAGE->requires->js_call_amd('mod_ctsr/show_total', 'init');

$PAGE->set_activity_record($ctsr);

$form = new form($PAGE->url->out(false), $customdata);
if ($data = $form->get_data()) {
    if (empty($data->id)) {
        $persistent = new ctsr(0, $data);
        $persistent->create();
    } else {
        $persistent->from_record($data);
        $persistent->update();
    }
}

// Output
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($ctsr->name), 2);
echo $OUTPUT->render(new ctsr_view($cmid, $form));
echo $OUTPUT->footer();
