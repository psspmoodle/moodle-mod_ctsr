<?php

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
 * Adds ctsr instance and creates new record in ctsr_complete table.
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
 * Updates ctsr instance.
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
    $DB->delete_records('ctsr', ['id' => $ctsr->id]);

    return true;
}