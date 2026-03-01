<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffMaster extends Model
{
    protected $table = 'StaffMaster';

    protected $fillable = [
        'user_name',
        'password',
        'role'
    ];
}
