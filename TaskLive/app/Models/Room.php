<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name','tag', 'time_limit', 'user_id'];

    public function tags()
    {
        return $this->belongsToMany(RoomTag::class, 'rooms_room_tags');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'room_users', 'room_id', 'user_id');
    }

}
