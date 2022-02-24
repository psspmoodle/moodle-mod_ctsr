<?php

require_once($CFG->dirroot . '/mod/ctsr/backup/moodle2/backup_ctsr_stepslib.php');

/**
 * ctsr backup task
 */
class backup_ctsr_activity_task extends backup_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // Choice only has one structure step
        $this->add_step(new backup_ctsr_activity_structure_step('ctsr_structure', 'ctsr.xml'));
    }

    /**
     * Code the transformations to perform in the activity in
     * order to get transportable (encoded) links
     */
    static public function encode_content_links($content) {
        return $content;
    }
}