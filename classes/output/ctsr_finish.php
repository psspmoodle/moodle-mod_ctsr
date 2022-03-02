<?php

namespace mod_ctsr\output;

use ArrayIterator;
use DOMXPath;
use html_table;
use html_table_cell;
use html_table_row;
use html_writer;
use mod_ctsr\domnode_iterator;
use mod_ctsr\util;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class ctsr_finish implements renderable, templatable
{

    private $ctsr;

    private $ctsruser;

    public function __construct($ctsr, $ctsruser)
    {
        $this->ctsr = $ctsr;
        $this->ctsruser = $ctsruser;
    }

    private function get_score_mean()
    {

    }

    private function parse_finish_content()
    {
        $rows = [];
        $items = explode('***', $this->ctsr->finish_content);
        foreach ($items as $item) {
            $domdoc = util::open_domdocument(markdown_to_html($item));
            $xpath = new DOMXPath($domdoc);
            $body = ($xpath->query('body'))->item(0);
            $iter = new domnode_iterator($body->childNodes);
            while ($iter->valid()) {
                $row['item'] = $this->out($iter);
                $row['exp_score'] = $this->out($iter);
                $row['exp_comments'] = $this->out($iter, true);
                $rows[] = $row;
                $row = [];
            }
        }
        return $rows;
    }

    /**
     * Outputs the node HTML and moves the iterator forward.
     *
     * @param $iter ArrayIterator
     * @param bool $html
     * @return mixed
     */
    private function out(ArrayIterator $iter, bool $html = false) {
        if ($html) {
            $item = $iter->current()->ownerDocument->saveHTML($iter->current());
        } else {
            $item = $iter->current()->textContent;
        }
        $iter->next();
        return $item;
    }

    private function make_finish_table()
    {
        $rows = [];
        foreach ($this->parse_finish_content() as $k => $v) {
            $item = "item_" . str_pad(($k + 1), 2, 0, STR_PAD_LEFT);
            $row = new stdClass();
            $content = new stdClass();
            $row->number = $k + 1;
            $row->item = $v['item'];
            $content->userscore = $this->ctsruser->get($item . "_score");
            $content->expertscore = $v['exp_score'];
            $content->meanscore = 'MEAN';
            $content->comments = $this->ctsruser->get($item . "_comments");
            $content->expertcomments = $v['exp_comments'];
            $row->content = $content;
            $rows[] = $row;
        }
        return $rows;
    }




    public function export_for_template(renderer_base $output)
    {
        $data = new stdClass();
        $data->finish = $this->make_finish_table();
        return $data;
    }
}