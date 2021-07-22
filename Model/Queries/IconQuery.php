<?php

namespace Inwebo\Favicon\Model\Queries;

class IconQuery extends Query
{
    protected string $query = '//link[@rel="icon"]';
}