<?php

namespace Inwebo\Favicon\Model\Queries;

class SvgQuery extends Query
{
    protected string $query = '//link[@type="image/svg+xml"]';
}