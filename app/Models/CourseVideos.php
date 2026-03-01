<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseVideos extends Model
{
    protected $table = 'CourseVideos';

    protected $fillable = [
        'course_id',
        'video_url',
        'thumbnal_url',
        'status'
    ];
}
