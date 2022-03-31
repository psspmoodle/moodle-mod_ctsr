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
        $ctsrs[] = new restore_path_element('ctsr', '/activity/ctsr');
        if ($this->get_setting_value('userinfo')) {
            $ctsrs[] = new restore_path_element('ctsr_user', '/activity/ctsr/ctsr_users/ctsr_user');
        }
        // Return the ctsrs wrapped into standard activity structure.
        return $this->prepare_activity_structure($ctsrs);
    }

    /**
     * @param $data
     * @return void
     * @throws base_step_exception
     * @throws dml_exception
     */
    protected function process_ctsr($data)
    {
        global $DB;
        $data = (object)$data;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        // Insert the ctsr activity record
        $newitemid = $DB->insert_record('ctsr', $data);
        // This must be called immediately after inserting a new activity record
        $this->apply_activity_instance($newitemid);
    }

    /**
     * @param $data
     * @return void
     * @throws dml_exception
     */
    protected function process_ctsr_user($data)
    {
        global $DB;
        $data = (object)$data;
        $data->ctsr_id = $this->get_new_parentid('ctsr');
        $DB->insert_record('ctsr_user', $data);
    }

    /**
     * @return void
     */
    protected function after_execute()
    {
        $this->add_related_files('mod_ctsr', 'intro', null);
    }

}