<?php

namespace mod_ctsr;

use core\form\persistent;

class ctsr_form extends persistent
{
    /** @var string Persistent class name. */
    protected static $persistentclass = 'mod_ctsr\\ctsr_user';

    /** @var array Fields to remove when getting the final data.
     * Note: not adding 'userid' results in the error "Unexpected property 'userid' requested."
     * This is because it doesn't exist as a field in the persistent.
     */
    protected static $fieldstoremove = ['submitbutton', 'userid', 'updatectsr'];

    /**
     * @inheritDoc
     */
    protected function definition()
    {
        $mform = $this->_form;

        // User ID
        $mform->addElement('hidden', 'user_id');
        $mform->setType('user_id', PARAM_INT);
        $mform->setConstant('user_id', $this->_customdata['user_id']);

        $mform->addElement('hidden', 'ctsr_id');
        $mform->setType('ctsr_id', PARAM_INT);
        $mform->setConstant('ctsr_id', $this->_customdata['ctsr_id']);

        $mform->addElement('select', 'item_01_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_01_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_01_comments', 'Comments:');
        $mform->setType('item_01_comments', PARAM_RAW);

        $mform->addElement('select', 'item_02_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_02_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_02_comments', 'Comments:');
        $mform->setType('item_02_comments', PARAM_RAW);

        $mform->addElement('select', 'item_03_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_03_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_03_comments', 'Comments:');
        $mform->setType('item_03_comments', PARAM_RAW);

        $mform->addElement('select', 'item_04_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_04_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_04_comments', 'Comments:');
        $mform->setType('item_04_comments', PARAM_RAW);

        $mform->addElement('select', 'item_05_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_05_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_05_comments', 'Comments:');
        $mform->setType('item_05_comments', PARAM_RAW);

        $mform->addElement('select', 'item_06_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_06_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_06_comments', 'Comments:');
        $mform->setType('item_06_comments', PARAM_RAW);

        $mform->addElement('select', 'item_07_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_07_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_07_comments', 'Comments:');
        $mform->setType('item_07_comments', PARAM_RAW);

        $mform->addElement('select', 'item_08_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_08_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_08_comments', 'Comments:');
        $mform->setType('item_08_comments', PARAM_RAW);

        $mform->addElement('select', 'item_09_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_09_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_09_comments', 'Comments:');
        $mform->setType('item_09_comments', PARAM_RAW);

        $mform->addElement('select', 'item_10_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_10_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_10_comments', 'Comments:');
        $mform->setType('item_10_comments', PARAM_RAW);

        $mform->addElement('select', 'item_11_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_11_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_11_comments', 'Comments:');
        $mform->setType('item_11_comments', PARAM_RAW);

        $mform->addElement('select', 'item_12_score', 'Score:', $this->make_scoring_select_items());
        $mform->setType('item_12_score', PARAM_TEXT);
        $mform->addElement('editor', 'item_12_comments', 'Comments:');
        $mform->setType('item_12_comments', PARAM_RAW);

        $mform->addElement('submit', 'updatectsr', 'Save progress');
        // Bootstrap tooltips need a disabled button to have 'pointer-events: none'
        $mform->addElement('submit', 'submitbutton', 'Submit', ['disabled' => 'disabled', 'style' => 'pointer-events: none']);
    }

    private function make_scoring_select_items()
    {
        $scores = ['0', '0.5', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0', '5.5', '6.0'];
        return [-1 => 'Select:'] + array_combine($scores, $scores);
    }
}