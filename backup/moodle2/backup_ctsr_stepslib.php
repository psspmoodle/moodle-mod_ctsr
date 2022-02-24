<?php

/**
 * @package    mod_ctsr
 * @subpackage backup-moodle2
 * @copyright 2022 Matt Donnelly
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_ctsr_activity_task
 */

class backup_ctsr_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // Whether we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $ctsr = new backup_nested_element('ctsr', ['id'],
            [
                'course',
                'name',
                'intro',
                'introformat',
                'timemodified'
            ]
        );
        $ctsrusers = new backup_nested_element('ctsr_users');
        $ctsruser = new backup_nested_element('ctsr_user', ['id'],
            [
                'ctsr_id',
                'user_id',
                'item_01_score',
                'item_01_comments',
                'item_01_commentsformat',
                'item_02_score',
                'item_02_comments',
                'item_02_commentsformat',
                'item_03_score',
                'item_03_comments',
                'item_03_commentsformat',
                'item_04_score',
                'item_04_comments',
                'item_04_commentsformat',
                'item_05_score',
                'item_05_comments',
                'item_05_commentsformat',
                'item_06_score',
                'item_06_comments',
                'item_06_commentsformat',
                'item_07_score',
                'item_07_comments',
                'item_07_commentsformat',
                'item_08_score',
                'item_08_comments',
                'item_08_commentsformat',
                'item_09_score',
                'item_09_comments',
                'item_09_commentsformat',
                'item_10_score',
                'item_10_comments',
                'item_10_commentsformat',
                'item_11_score',
                'item_11_comments',
                'item_11_commentsformat',
                'item_12_score',
                'item_12_comments',
                'item_12_commentsformat',
                'usermodified',
                'timecreated',
                'timemodified'
            ]
        );

        // Build the tree
        $ctsr->add_child($ctsrusers);
        $ctsrusers->add_child($ctsruser);

        // Define sources
        $ctsr->set_source_table('ctsr', ['id' => backup::VAR_ACTIVITYID]);

        // Only happen if we are including user info
        if ($userinfo) {
            $ctsruser->set_source_table('ctsr_user', ['path_id' => backup::VAR_PARENTID]);
        }

        // Return the root element (ctsr), wrapped into standard activity structure
        return $this->prepare_activity_structure($ctsr);
    }
}
