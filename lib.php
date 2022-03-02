<?php

use mod_ctsr\ctsr_user;
use mod_ctsr\util;

function ctsr_supports($feature): ?bool {
    switch ($feature) {
        case FEATURE_GROUPINGS:
        case FEATURE_GRADE_HAS_GRADE:
        case FEATURE_GRADE_OUTCOMES:
        case FEATURE_GROUPS:                  return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
        case FEATURE_COMPLETION_HAS_RULES:
        case FEATURE_BACKUP_MOODLE2:
        case FEATURE_SHOW_DESCRIPTION:
        case FEATURE_MOD_INTRO:               return true;
        default: return null;
    }
}

/**
 * Add ctsr instance.
 *
 * @param stdClass $data
 * @param mod_ctsr_mod_form|null $mform
 * @return int new ctsr instance id
 * @throws dml_exception
 */
function ctsr_add_instance(stdClass $data, mod_ctsr_mod_form $mform = null): int {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");
    $cmid = $data->coursemodule;
    $data->timemodified = time();
    $data->id = $DB->insert_record('ctsr', $data);
    $DB->set_field('course_modules', 'instance', $data->id, array('id'=>$cmid));
    return $data->id;
}

/**
 * Update ctsr instance.
 *
 * @param $data
 * @param $mform
 * @return bool
 * @throws dml_exception
 */
function ctsr_update_instance($data, $mform): bool {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");
    $cmid = $data->coursemodule; // Needed for context if we were using files
    $data->timemodified = time();
    $data->id = $data->instance;
    $DB->update_record('ctsr', $data);
    return true;
}

/**
 * Deletes ctsr instance.
 *
 * @param int $id
 * @return bool true
 * @throws coding_exception
 * @throws dml_exception
 */
function ctsr_delete_instance(int $id): bool {
    global $DB;
    if (!$ctsr = $DB->get_record('ctsr', ['id' => $id])) {
        return false;
    }
    foreach (ctsr_user::get_records(['ctsr_id' => $id]) as $record) {
        $record->delete();
    }
    $DB->delete_records('ctsr', ['id' => $ctsr->id]);

    return true;
}

/**
 *
 * @param object $course Course
 * @param object $cm Course-module
 * @param int $userid User ID
 * @param bool $type Type of comparison (or/and; can be used as return value if no conditions)
 * @return bool True if completed, false if not, $type if conditions not set.
 * @throws dml_exception
 */
function ctsr_get_completion_state(object $course, object $cm, int $userid, bool $type): bool {
    global $DB;
    $ctsr = $DB->get_record('ctsr', ['id' => $cm->instance], '*', MUST_EXIST);
    // If completion option is enabled, evaluate it and return true/false
    if ($ctsr->completion_submission) {
        // We are just looking for a single record marked finished
        return (bool)ctsr_user::count_records(['user_id' => $userid, 'ctsr_id' => $ctsr->id, 'submitted' => 1]);
    } else {
        return $type;
    }
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param stdClass $data Data submitted from reset course.
 * @return array status array
 * @throws dml_exception
 */
function ctsr_reset_userdata($data): array
{
    global $DB;
    $status = [];
    if (isset($data->reset_user_progress)) {
        $param = ['courseid' => $data->id];
         $sql = "ctsr_id IN (
                SELECT id from {ctsr} c
                WHERE c.course = :courseid)";
        $DB->delete_records_select('ctsr_user', $sql, $param);
        $status[] = [
            'component' => util::s('modulenameplural'),
            'item' => util::s('ctsr_reset_all_user_progress'),
            'error' => false
        ];
    }
    return $status;
}

/**
 * Called by course/reset.php
 */
function ctsr_reset_course_form_definition(&$mform)
{
    $mform->addElement('header', 'ctsrheader',  util::s('modulenameplural'));
    $mform->addElement('checkbox', 'reset_user_progress', util::s('ctsr_reset_all_user_progress'));
}

function ctsr_reset_course_form_defaults($course)
{
    return ['reset_user_progress' => 1];
}