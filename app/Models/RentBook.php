<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentBook extends Model
{
    use HasFactory;

    protected $fillable = [

	'id',
	'user_id',
	'book_id',
	'star_time',
	'end_time',
	'is_taken',
	'created_at',
	'updated_at',
    ];

    public function book()
    {
        return $this->hasOne(Book::class,'id', 'book_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'id');
    }
}
