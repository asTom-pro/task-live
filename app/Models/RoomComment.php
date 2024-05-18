<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomComment extends Model
{
    use HasFactory;


    protected $fillable = ['comment', 'room_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
