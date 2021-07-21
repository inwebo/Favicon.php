<?php

namespace Inwebo\Favicon\Model\Strategies;

class DefaultStrategy extends StrategyAbstract
{
    public function __construct(\DOMDocument $document, ?string $query = null)
    {
        $this->domXpath = new \DOMXPath($document);
        $this->query    = '//link[@rel="shortcut icon"]';
    }
}