<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaster extends Model
{
    protected $table = 'CourseMaster';

    protected $fillable = [
        'title',
        'description'
    ];
}
