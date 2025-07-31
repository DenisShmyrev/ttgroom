<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'start_time',
        'end_time',
        'participants_count',
        'notes',
        'status'
    ];
    protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

public function room(){
        return $this->belongsTo(Room::class);
    }
}
