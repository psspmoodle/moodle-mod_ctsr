<?php

use core\invalid_persistent_exception;
use mod_ctsr\ctsr;

require_once("$CFG->libdir/externallib.php");

class mod_ctsr_external extends external_api {

    /**
     * @param $params
     * @return void
     * @throws invalid_persistent_exception
     * @throws coding_exception
     * @throws invalid_parameter_exception
     */
    public static function update_tab($params)
    {
        $validated = self::validate_parameters(self::update_tab_parameters(), $params);
        $ctsr = new ctsr($validated['ctsr_id']);
        $ctsr->set('current', $validated['tab']);
        $ctsr->update();
    }

    /**
     * @return external_function_parameters
     */
    public static function update_tab_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            [
                'ctsr_id' => new external_value(PARAM_INT, VALUE_REQUIRED),
                'tab' => new external_value(PARAM_INT, VALUE_REQUIRED)
            ]
        );
    }

    public static function update_tab_returns() {
    }
}