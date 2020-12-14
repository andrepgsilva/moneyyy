<?php

namespace App\Scopes\API\Bills;

trait Scopes 
{
    /**
     * Scope a query to only include bills in a month
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  String|Integer $month
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMonth($query, $month)
    {
        $month = now()->month((Integer) $month);
        
        return $query
                ->whereDate('issue_date', '>=', $month->firstOfMonth())
                ->whereDate('issue_date', '<=', $month->endOfMonth());
    }

    /**
     * Scope a query to only include previous month bills
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePreviousMonth($query)
    {
        return $query
                ->whereDate('issue_date', '>=', now()->subMonth()->firstOfMonth())
                ->whereDate('issue_date', '<', now()->firstOfMonth());
    }

    /**
     * Scope a query to only include bills between a date range
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Array $dateRange
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopebyDate($query, Array $dateRange)
    {
        if (count($dateRange) != 2) {
            return $query;
        }

        $dateStart = $dateRange[0];
        $dateEnd = $dateRange[1];
        
        return $query
                ->whereDate('issue_date', '>=', $dateStart)
                ->whereDate('issue_date', '<=', $dateEnd);
    }
}

