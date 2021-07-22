<?php

namespace Inwebo\Favicon\Model\Queries;

class Query implements QueryInterface
{
    protected string $query;

    public function query(\DOMXPath $path): \DOMNodeList
    {
        return $path->query($this->query);
    }
}