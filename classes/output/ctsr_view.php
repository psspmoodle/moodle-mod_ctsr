<?php

namespace mod_ctsr\output;

use coding_exception;
use DOMXPath;
use renderable;
use stdClass;
use templatable;
use renderer_base;
use mod_ctsr\util;


class ctsr_view implements renderable, templatable
{

    private $cmid;
    private $form;

    public function __construct($cmid, $form)
    {
        $this->cmid = $cmid;
        $this->form = $form;
    }

    private function get_next_non_textnode($node) {
        foreach ($node->childNodes as $child) {
            if ($child->nodeType !== XML_TEXT_NODE) {
                return $child;
            }
        }
        return null;
    }

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
     * @throws coding_exception
     */
    public function export_for_template(renderer_base $output)
    {
        $data = new stdClass;
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

        // Close form
        $data->formclose = '</form>';

        $html = $form->ownerDocument->saveHTML($form);



        return $data;
    }
}