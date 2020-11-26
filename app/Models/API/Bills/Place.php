<?php

namespace App\Models\API\Bills;

use App\Models\API\Bills\Bill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends Model
{
    use HasFactory;

    public function bills()
    {
        return $this->belongsToMany(Bill::class);
    }
}
