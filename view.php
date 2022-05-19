<?php

/**
 * view.php
 *
 * @package     mod_ctsr
 * @copyright   2022 Matt Donnelly
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_ctsr\ctsr_user;
use mod_ctsr\ctsr_form as form;
use mod_ctsr\output\ctsr_view;

require('../../config.php');
require_once($CFG->dirroot.'/mod/ctsr/lib.php');
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
    // Update Moodle completion
    $completion = new completion_info($COURSE);
    $completion->set_module_viewed($cm);
    // Set persistent
    $persistent = ctsr_user::get_record(['ctsr_id' => $ctsr->id, 'user_id' => $USER->id]) ?: null;
    // Redirect if already submitted
    if ($persistent && $persistent->get('submitted')) {
        redirect(new moodle_url('/mod/ctsr/finish.php', ['id' => $cmid]));
    }
}

$customdata = [
    'persistent' => $persistent,
    'ctsr_id' => $ctsr->id,
    'user_id' => $USER->id
];

// $PAGE setup
$PAGE->set_url('/mod/ctsr/view.php', ['id' => $cmid]);
$PAGE->set_activity_record($ctsr);
$PAGE->set_title($COURSE->shortname. ': '. $ctsr->name);
$PAGE->set_heading($COURSE->fullname);

$form = new form($PAGE->url->out(false), $customdata);

if (!$isadmin) {
    if ($data = $form->get_data()) {
        if (empty($data->id)) {
            $persistent = new ctsr_user(0, $data);
            $persistent->create();
        } else {
            $persistent->from_record($data);
            if (optional_param('submitbutton', '', PARAM_TEXT)) {
                $persistent->set('submitted', 1);
            }
            $persistent->update();
            // Always redirect for both Save Progress and Submit to prevent back button issues
            redirect(new moodle_url('/mod/ctsr/finish.php', ['id' => $cmid]));
        }
    }
}
// Load JS module
$PAGE->requires->js_call_amd('mod_ctsr/ctsr', 'init');

// Output
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($ctsr->name), 2);
echo $OUTPUT->render(new ctsr_view($cmid, $ctsr, $form, $isadmin));
echo $OUTPUT->footer();