<?php

namespace Inwebo\Favicon\Model\Queries;

class AppleTouchPrecomposedQuery extends Query
{
    protected string $query = '//link[@rel="mask-icon"]';
}