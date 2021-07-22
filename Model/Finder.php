<?php

namespace Inwebo\Favicon\Model;

use Symfony\Component\Console\Output\Output;

class Finder
{
    protected \DOMDocument      $document;
    protected \DOMXPath         $xPath;
    protected \SplObjectStorage $queries;
    protected ?Output           $output;

    public function getQueries(): \SplObjectStorage
    {
        return $this->queries;
    }

    public function __construct(\DOMDocument $document, ?Output $output = null)
    {
        $this->document = $document;
        $this->xPath    = new \DOMXPath($this->document);
        $this->queries  = new \SplObjectStorage();
        $this->output   = $output;
    }

    public function find(): array
    {
        $return = [];
        $this->queries->rewind();
        while ($this->queries->valid()) {
            /** @var \DOMNodeList $domNodeList */
            $domNodeList = $this->queries->current()->query($this->xPath);

            if ($domNodeList->length > 0) {
                array_push($return, ...$domNodeList);
            }

            $this->queries->next();
        }

        return $return;
    }
}