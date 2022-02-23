<?php

defined('MOODLE_INTERNAL') || die;

$functions = [
    'mod_ctsr_update_tab' => [
        'classname'     => 'mod_ctsr_external',
        'methodname'    => 'update_tab',
        'description'   => 'Update a user\'s CTSR activity tab',
        'type'          => 'write',
        'capabilities'  => 'mod/ctsr:view',
        'ajax'          => true
    ]
];
