<?php

$capabilities = [
//    'mod/path:view' => [
//        'captype' => 'read',
//        'contextlevel' => CONTEXT_MODULE,
//        'archetypes' => [
//            'user' => CAP_ALLOW,
//        ]
//    ],
    'mod/ctsr:addinstance' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE
    ],
    'mod/ctsr:manageinstance' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE
    ]
];