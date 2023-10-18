<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        
	'id',
	'department_id',
	'name',
	'tag',
	'position',
	'description',
	'created_at',
	'updated_at',
    ];
}
