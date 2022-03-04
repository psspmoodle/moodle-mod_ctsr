<?php

namespace mod_ctsr\output;

use coding_exception;
use context;
use context_module;
use DOMXPath;
use mod_ctsr\ctsr_form;
use moodle_exception;
use moodle_url;
use renderable;
use stdClass;
use templatable;
use renderer_base;
use mod_ctsr\util;


class ctsr_view implements renderable, templatable
{
    /**
     * @var $cmid int
     */
    private $cmid;

    /**
     * @var $ctsr object
     */
    private $ctsr;

    /**
     * @var $form ctsr_form The user input form.
     */
    private $form;

    /**
     * @var $isadmin bool
     */
    private $isadmin;

    /**
     * @var bool|context|context_module
     */
    private $context;

    /**
     * @param int $cmid
     * @param object $ctsr
     * @param $form ctsr_form
     * @param bool $isadmin
     */
    public function __construct(int $cmid, object $ctsr, ctsr_form $form, bool $isadmin = false)
    {
        $this->cmid = $cmid;
        $this->ctsr = $ctsr;
        $this->form = $form;
        $this->isadmin = $isadmin;
        $this->context = context_module::instance($cmid);
    }

    /**
     * @param $node
     * @return mixed|null
     */
    private function get_next_non_textnode($node)
    {
        foreach ($node->childNodes as $child) {
            if ($child->nodeType !== XML_TEXT_NODE) {
                return $child;
            }
        }
        return null;
    }

    /**
     * Item titles.
     *
     * @param $key
     * @return string
     */
    private function get_item_title($key): string
    {
        $titles = [
            'Agenda setting & adherence',
            'Feedback',
            'Collaboration',
            'Pacing & efficient use of time',
            'Interpersonal effectiveness',
            'Eliciting of appropriate emotional expression',
            'Eliciting key cognitions',
            'Eliciting & planning behaviours',
            'Guided discovery',
            'Conceptual integration',
            'Application of change methods',
            'Homework setting'
        ];
        return $titles[$key];
    }

    /**
     * Turn Moodle's form output into something more flexible for tabbed display. Liberal use of DOMDocument.
     *
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function export_for_template(renderer_base $output): stdClass
    {
        $data = new stdClass;
        $data->intro = file_rewrite_pluginfile_urls($this->ctsr->intro, 'pluginfile.php', $this->context->id, 'mod_ctsr', 'intro', null);
        $domdoc = util::open_domdocument($this->form->render());
        $xpath = new DOMXPath($domdoc);
        // Form element and children
        $form = ($xpath->query('//form'))->item(0);
        // Just form element
        $formelement = $form->cloneNode();
        // </form> removed
        $data->formopen = substr($formelement->ownerDocument->saveHTML($formelement), 0, -7);
        // Hidden fields (userid, sesskey, form name)
        $hiddenfields = $this->get_next_non_textnode($form);
        $data->hiddenfields = $hiddenfields->ownerDocument->saveHTML($hiddenfields);
        $labels = $xpath->query('//form//label');
        $fields = $xpath->query('//form//div[@class="col-md-8 form-inline felement"]');
        for ($i = 0; $i < 12; $i++) {
            $data->tabs[$i]['item'] = $i + 1;
            $data->tabs[$i]['title'] = $this->get_item_title($i);
            $label = $labels->item($i * 2);
            $label->setAttribute('class', 'mb-0');
            $data->tabs[$i]['scorelabel'] = $label->ownerDocument->saveHTML($label);
            $field = $fields->item($i * 2);
            $field->removeAttribute('class');
            $data->tabs[$i]['scorefield'] = $field->ownerDocument->saveHTML($field);
            $label = $labels->item($i * 2 + 1);
            $label->setAttribute('class', 'mb-0');
            $data->tabs[$i]['commentslabel'] = $label->ownerDocument->saveHTML($label);
            $field = $fields->item($i * 2 + 1);
            $field->removeAttribute('class');
            $data->tabs[$i]['commentsfield'] = $field->ownerDocument->saveHTML($field);
        }
        $update = $fields->item($fields->count() - 2);
        $update->removeAttribute('class');
        $submit = $fields->item($fields->count() - 1);
        $submit->removeAttribute('class');
        $data->update = $update->ownerDocument->saveHTML($update);
        $data->submit = $submit->ownerDocument->saveHTML($submit);
        if ($this->isadmin) {
            $data->finishpage = new moodle_url('/mod/ctsr/finish.php', ['id' => $this->cmid]);
        }
        // Close form
        $data->formclose = '</form>';
        return $data;
    }
}