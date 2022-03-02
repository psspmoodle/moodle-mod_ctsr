<?php

defined('MOODLE_INTERNAL') || die;

function xmldb_ctsr_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022022806) {


        // Define field finish_content to be added to ctsr.
        $table = new xmldb_table('ctsr');
        $field = new xmldb_field('finish_content', XMLDB_TYPE_TEXT, null, null, null, null, null, 'introformat');

        // Conditionally launch add field finish_content.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field finish_contentformat to be added to ctsr.
        $table = new xmldb_table('ctsr');
        $field = new xmldb_field('finish_contentformat', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '4', 'finish_content');

        // Conditionally launch add field finish_contentformat.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // path savepoint reached.
        upgrade_mod_savepoint(true, 2022022806, 'ctsr');
    }
    return true;
}