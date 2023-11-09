<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        
	'id',
	'lesson_id',
	'video_id',
	'start_time',
	'end_time',
    ];
}
