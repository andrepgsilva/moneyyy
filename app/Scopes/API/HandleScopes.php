<?php

namespace App\Scopes\API;

use Illuminate\Support\Str;

class HandleScopes implements FiltersInterface
{
    /**
     * Add scopes to a query
     *
     * @param Illuminate\Database\Eloquent\Builder $currentQuery
     * @param Array $scopeNames
     * 
     * @return Illuminate\Database\Eloquent\Builder
     **/
    public function setScopes($currentQuery, Array $scopeNames)
    {
        if (! count($scopeNames)) return $currentQuery;

        foreach ($scopeNames as $scopeName => $scopeValues) {
            $scope = Str::camel($scopeName);

            try {
                $currentQuery->$scope($scopeValues);
            } catch(\Exception $e) {
                continue;
            }
        }

        return $currentQuery;
    }
}