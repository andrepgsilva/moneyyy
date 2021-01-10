<?php

namespace App\Models\API\Password;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'confirmation_token',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
