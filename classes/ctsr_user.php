<?php


namespace mod_ctsr;

use core\persistent;

defined('MOODLE_INTERNAL') || die();

class ctsr_user extends persistent
{

    const TABLE = 'ctsr_user';

    /**
     * Return the definition of the properties of this model.
     *
     * @return array[]
     */
    protected static function define_properties(): array
    {
        return array(
            'ctsr_id' => array(
                'type' => PARAM_INT
            ),
            'user_id' => array(
                'type' => PARAM_INT,
            ),
            'item_01_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_01_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_01_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_02_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_02_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_02_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_03_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_03_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_03_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_04_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_04_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_04_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_05_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_05_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_05_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_06_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_06_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_06_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_07_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_07_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_07_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_08_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_08_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_08_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_09_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_09_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_09_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_10_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_10_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_10_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_11_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_11_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_11_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'item_12_score' => array(
                'type' => PARAM_TEXT,
                'default' => 0.0
            ),
            'item_12_comments' => array(
                'type' => PARAM_RAW,
                'default' => ''
            ),
            'item_12_commentsformat' => array(
                'type' => PARAM_INT,
                'default' => 1
            ),
            'submitted' => array(
                'type' => PARAM_INT,
                'default' => 0
            ),
        );
    }
}