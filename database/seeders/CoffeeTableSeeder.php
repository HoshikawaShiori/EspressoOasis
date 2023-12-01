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
                'imagePath' => 'src/images/carmach.png',
                'title' => 'Cappuccino',
                'sizes' => [
                    ['label' => 'S', 'price' => 70.00],
                    ['label' => 'M', 'price' => 90.00],
                    ['label' => 'L', 'price' => 110.00],
                ],
            ],
            [
                'imagePath' => 'src/images/carmach.jpg',
                'title' => 'Caramel Macchiato',
                'sizes' => [
                    ['label' => 'S', 'price' => 100.00],
                    ['label' => 'M', 'price' => 120.00],
                    ['label' => 'L', 'price' => 140.00],
                ],
            ],
            [
                'imagePath' => 'src/images/capuccino.jpg',
                'title' => 'Frappuccino',
                'sizes' => [
                    ['label' => 'S', 'price' => 80.00],
                    ['label' => 'M', 'price' => 100.00],
                    ['label' => 'L', 'price' => 120.00],
                ],
            ],
        ];
        
        foreach ($data as $row) {
            $sizes = $row['sizes']; // Extract sizes array
            unset($row['sizes']); // Remove sizes from the main array
        
            $coffee = Coffee::create($row); // Create the coffee item without sizes
        
            // Associate sizes with the created coffee item
            $coffee->update(['sizes' => $sizes]);
        }
    }
}
