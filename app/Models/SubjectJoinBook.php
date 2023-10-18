<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectJoinBook extends Model
{
    use HasFactory;

    protected $fillable = [
        
	'id',
	'subject_id',
	'book_id',
	'created_at',
	'updated_at',
    ];
}
