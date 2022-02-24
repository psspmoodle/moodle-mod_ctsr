<?php

/**
 * @package     mod_ctsr
 * @copyright   2022
 * @author      Matt Donnelly
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define all the restore steps that will be used by the restore_ctsr_activity_task
 */

/**
 * Structure step to restore one ctsr activity
 */
class restore_ctsr_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $ctsrs = [];
        $userinfo = $this->get_setting_value('userinfo');

        $ctsrs[] = new restore_ctsr_element('ctsr', '/activity/ctsr');

        if ($userinfo) {
            $ctsrs[] = new restore_ctsr_element('ctsr_user', '/activity/ctsr/ctsrusers/ctsruser');
        }

        // Return the ctsrs wrapped into standard activity structure.
        return $this->prepare_activity_structure($ctsrs);
    }

    protected function process_ctsr($data) {
        global $DB;

        $data = (object)$data;
        $data->course = $this->get_courseid();
        $this->opening_frame = $data->opening_frame;
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Insert the ctsr activity record
        $newitemid = $DB->insert_record('ctsr', $data);
        // This must be called immediately after inserting a new activity record
        $this->apply_activity_instance($newitemid);
    }

    protected function process_ctsr_user($data) {
        global $DB;
        $data = (object)$data;
        $data->ctsr_id = $this->get_new_parentid('ctsr');
        $DB->insert_record('ctsr_user', $data);
    }

}