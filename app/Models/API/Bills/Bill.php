<?php

namespace App\Models\API\Bills;

use App\Models\API\Bills\Place;
use App\Models\API\Bills\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'value'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function places()
    {
        return $this->belongsToMany(Place::class);
    }
}