<?php

namespace Inwebo\Favicon\Model\Queries;

interface QueryInterface
{
    public function query(\DOMXPath $path): \DOMNodeList;
}