<?php

namespace mod_ctsr;

use ArrayIterator;
use DOMNodeList;

class domnode_iterator extends ArrayIterator
{
    public function __construct (DOMNodeList $node_list)
    {
        $nodes = [];
        foreach($node_list as $node) {
            if ($node->nodeType !== XML_TEXT_NODE) {
                $nodes[] = $node;
            }
        }
        parent::__construct($nodes);
    }

    public function hasChildren ()
    {
        return $this->current()->hasChildNodes();
    }

    public function getChildren (): domnode_iterator
    {
        return new self($this->current()->childNodes);
    }

}