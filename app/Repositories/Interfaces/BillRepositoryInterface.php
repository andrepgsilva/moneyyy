<?php

namespace App\Repositories\Interfaces;

use App\Models\API\Bills\Bill;

interface BillRepositoryInterface {
    public function index();

    public function show($id);

    public function store(array $billInformation);

    public function update(Bill $bill, $billInformation);
    
    public function destroy(Bill $bill);
}