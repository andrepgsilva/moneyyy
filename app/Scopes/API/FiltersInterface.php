<?php

namespace App\Scopes\API;

use Illuminate\Database\Eloquent\Builder;

interface FiltersInterface 
{
    function setScopes(Builder $query, Array $scopeNames);
}