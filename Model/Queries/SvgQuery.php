<?php

namespace Inwebo\Favicon\Model\Queries;

class SvgQuery extends Query
{
    protected string $query = '//link[@rel="apple-touch-icon-precomposed"]';
}