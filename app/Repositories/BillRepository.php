<?php

namespace App\Repositories;

use App\Models\API\Bills\Bill;
use App\Models\API\Bills\Category;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\BillRepositoryInterface;

class BillRepository implements BillRepositoryInterface
{
    /**
     * Get all bills
     *
     * @return Illuminate\Database\Eloquent\Builder
     * 
     **/
    public function index()
    {
        $user = auth()->user();

        return $user->bills()
            ->with(['categories:id,name'])
            ->select([
                'bills.id',
                'bills.name',
                'bills.description',
                'bills.value',
                'bills.created_at',
                'bills.issue_date',
            ])
            ->withCasts([
                'created_at' => 'datetime:d/m/Y H:i',
                // 'issue_date' => 'datetime:d/m/Y H:i',
            ]);
    }

    /**
     * Get a bill
     *
     * @param Integer $id Bill identification
     * 
     * @return App\Models\API\Bills\Bill
     * 
     **/
    public function show($id)
    {
        $user = auth()->user();
        $scope = ['categories:id,name,slug'];

        return $user->bills()->with($scope)->where('bills.id', $id)->first();
    }

    /**
     * Store a bill
     *
     * @param Array $billInformation (name, description, value)
     * 
     * @return Boolean|App\Models\API\Bills\Bill  
     **/
    public function store(array $billInformation)
    {
        $bill = auth()->user()->bills()->create($billInformation);

        if (!$category = Category::find($billInformation['category_id'])) {
            return false;
        }

        $bill->categories()->attach($category->id);

        return $bill;
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
        if (!$category = Category::find($billInformation['category_id'])) {
            return false;
        }

        $bill->categories()->attach($category->id);

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
        DB::transaction(function () use ($bill) {
            $bill->categories()->detach();

            $bill->delete();
        });
    }
}
