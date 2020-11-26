<?php

namespace App\Repositories;

use App\Models\API\Bills\Bill;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\BillRepositoryInterface;

class BillRepository implements BillRepositoryInterface
{
    /**
     * Get a bill
     *
     * @param Integer $id Bill identification
     * 
     * @return App\Models\API\Bills\Bill
     * 
     * @throws \Throwable
     **/
    public function show($id)
    {
        return Bill::with(['categories:id,name,slug', 'places:id,name'])->findOrFail($id);
    }

    /**
     * Store a bill
     *
     * @param Array $billInformation (name, description, value)
     * 
     * @return App\Models\API\Bills\Bill
     **/
    public function store(array $billInformation)
    {
        return Bill::create($billInformation);
    }

    /**
     * Update a bill
     *
     * @param App\Models\API\Bills\Bill $bill
     *  
     * @return Boolean
     **/
    public function update(Bill $bill, $billInformation)
    {
        return $bill->update($billInformation);
    }

    /**
     * Delete a bill
     *
     * @param App\Models\API\Bills\Bill $bill bill model
     * 
     * @return void
     * 
     * @throws \Throwable
     **/
    public function destroy(Bill $bill)
    {
        DB::transaction(function () use ($bill){
            $bill->categories()->detach();
            $bill->places()->detach();

            $bill->delete();
        });
    }
}
