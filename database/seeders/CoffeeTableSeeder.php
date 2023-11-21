<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coffee;

class CoffeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'imagePath'=> 'src/images/carmach.png',
                'title'=> 'Cappucino',
                'price'=> 70.00,
            ],
            [
                'imagePath'=> 'src/images/carmach.png',
                'title'=> 'Caramel Macchiato',
                'price'=> 100.00,
            ],
    ];
    foreach ($data as $row) {
        Coffee::create($row);
    }
    }
}
