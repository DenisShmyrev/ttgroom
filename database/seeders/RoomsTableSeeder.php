<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomsTableSeeder extends Seeder
{
    public function run()
    {
        Room::create([
            'name' => 'D&D Комната',
            'description' => 'Стол с экраном для карт, миниатюры, грифельная доска',
            'capacity' => 6,
            'price_per_hour' => 500
        ]);

        Room::create([
            'name' => 'Монополия Премиум',
            'description' => 'Классическая и редкие версии игры, кофейный столик',
            'capacity' => 4,
            'price_per_hour' => 300
        ]);

        Room::create([
            'name' => 'Мафия Lounge',
            'description' => 'Звукоизолированная комната с тематическим декором',
            'capacity' => 10,
            'price_per_hour' => 700
        ]);
    }
}