<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'db_name',
        'db_host',
        'db_user',
        'db_password',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 