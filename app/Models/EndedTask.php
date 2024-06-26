<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndedTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'ended_task',
    ];
}
