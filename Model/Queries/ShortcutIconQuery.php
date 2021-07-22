<?php

namespace Inwebo\Favicon\Model\Queries;

class ShortcutIconQuery extends Query
{
    protected string $query = '//link[@rel="shortcut icon"]';
}