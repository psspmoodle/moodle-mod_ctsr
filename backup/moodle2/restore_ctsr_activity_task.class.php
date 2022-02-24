<?php

/**
 * @package    mod_ctsr
 * @copyright  2022 Matt Donnelly
 * @author     Matt Donnelly
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Because it exists (must).
require_once($CFG->dirroot . '/mod/ctsr/backup/moodle2/restore_ctsr_stepslib.php');

/**
 * questionnaire restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
class restore_ctsr_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Define (add) particular steps this activity can have
     * @throws base_task_exception
     */
    protected function define_my_steps() {
        // Choice only has one structure step.
        $this->add_step(new restore_ctsr_activity_structure_step('ctsr_structure', 'ctsr.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents(): array
    {
        return [];
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules(): array
    {
        return [];
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * questionnaire logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules(): array
    {
        $rules = [];
        $rules[] = new restore_log_rule('ctsr', 'add', 'view.php?id={course_module}', '{ctsr}');
        $rules[] = new restore_log_rule('ctsr', 'update', 'view.php?id={course_module}', '{ctsr}');
        $rules[] = new restore_log_rule('ctsr', 'view', 'view.php?id={course_module}', '{ctsr}');
        return $rules;
    }
}
