<?php

namespace mod_ctsr\output;

use ArrayIterator;
use coding_exception;
use core\persistent;
use DOMXPath;
use mod_ctsr\ctsr_user;
use mod_ctsr\domnode_iterator;
use mod_ctsr\util;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class ctsr_finish implements renderable, templatable
{

    /**
     * @var $ctsr object CTSR activity DB record.
     */
    private $ctsr;

    /**
     * @var $ctsruser ctsr_user Persistent of user record.
     */
    private $ctsruser;

    /**
     * @var bool
     */
    private $isadmin;

    /**
     * @var $records ctsr_user[]
     */
    private $records;

    /**
     * @var $totalexpertscore float
     */
    private $totalexpertscore;

    /**
     * @var $totaluserscore float
     */
    private $totaluserscore;

    /**
     * @param object $ctsr
     * @param $ctsruser ctsr_user|null Null if admin.
     */
    public function __construct(object $ctsr, persistent $ctsruser = null)
    {
        $this->ctsr = $ctsr;
        $this->ctsruser = $ctsruser;
        $this->isadmin = !$ctsruser;
        $this->records = ctsr_user::get_records(['ctsr_id' => $this->ctsr->id, 'submitted' => 1]);

    }

    /**
     * Transform the experts scores/comments from markdown to text/HTML.
     *
     * @return array
     */
    private function parse_finish_content(): array
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
     * Output the node HTML and move the iterator forward.
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

    /**
     * Prepare data for output.
     *
     * @return array
     * @throws coding_exception
     */
    private function make_finish_tables(): array
    {
        $rows = [];
        foreach ($this->parse_finish_content() as $k => $v) {
            $itemnum = $k + 1;
            $item = "item_" . str_pad($itemnum, 2, 0, STR_PAD_LEFT);
            $row = new stdClass();
            $content = new stdClass();
            $row->number = $itemnum;
            $row->item = $v['item'];
            $content->userscore = !$this->isadmin ? $this->ctsruser->get($item . "_score") : null;
            $this->totaluserscore += $content->userscore;
            $content->expertscore = $v['exp_score'];
            $this->totalexpertscore += $v['exp_score'];
            $content->meanscore = $this->num($this->get_mean_score(str_pad($itemnum, 2, 0, STR_PAD_LEFT)));
            $content->comments = !$this->isadmin ? $this->ctsruser->get($item . "_comments") : null;
            $content->expertcomments = $v['exp_comments'];
            $row->content = $content;
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Get the mean of all user scores for an item in a CTSR activity.
     *
     * @param $item
     * @return float
     * @throws coding_exception
     */
    private function get_mean_score($item): float
    {
        if (!$this->records) {
            return 0.0;
        }
        $scores = array_map(function($v) use ($item) {
            return (float) $v->get('item_' . $item . '_score');
        }, $this->records);
        return array_sum($scores) / count($this->records);
    }

    /**
     * Get the mean of total user scores of all users.
     *
     * @return float
     */
    private function get_total_mean_score(): float
    {
        if (!$this->records) {
            return 0.0;
        }
        $rowsums = [];
        foreach ($this->records as $record) {
            $rowsum = 0;
            foreach ((array) $record->to_record() as $key => $val) {
                if (strpos($key, '_score') !== false) {
                    $rowsum += $val;
                }
            }
            $rowsums[] = $rowsum;
        }
        return array_sum($rowsums) / count($this->records);
    }

    /**
     * Wrapper to ensure 1 decimal point.
     *
     * @param $num
     * @return string
     */
    private function num($num)
    {
        return number_format($num, 1);
    }


    /**
     * @param renderer_base $output
     * @return stdClass
     * @throws coding_exception
     */
    public function export_for_template(renderer_base $output): stdClass
    {
        $data = new stdClass();
        $data->finish = $this->make_finish_tables();
        $data->totaluserscore = $this->num($this->totaluserscore);
        $data->totalexpertscore = $this->num($this->totalexpertscore);
        $data->totalmeanscore = $this->num($this->get_total_mean_score());
        return $data;
    }
}