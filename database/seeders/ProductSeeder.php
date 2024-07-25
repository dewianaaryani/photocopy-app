<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'photocopy',
            'cetakfoto',
            'printout',
            'laminating',
            'jilid',
            'pakaian',
            'alat tulis'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }

        $products = [
            // Photocopy
            ['name' => 'Hitam Putih F4', 'category_id' => Category::where('name', 'photocopy')->first()->id, 'size' => 'F4', 'color_type' => 'hitamputih', 'price' => 200],
            ['name' => 'Hitam Putih A4', 'category_id' => Category::where('name', 'photocopy')->first()->id, 'size' => 'A4', 'color_type' => 'hitamputih', 'price' => 200],
            ['name' => 'Hitam Putih A3', 'category_id' => Category::where('name', 'photocopy')->first()->id, 'size' => 'A3', 'color_type' => 'hitamputih', 'price' => 1000],
            ['name' => 'Warna A4', 'category_id' => Category::where('name', 'photocopy')->first()->id, 'size' => 'A4', 'color_type' => 'warna', 'price' => 3000],
            ['name' => 'Warna F4', 'category_id' => Category::where('name', 'photocopy')->first()->id, 'size' => 'F4', 'color_type' => 'warna', 'price' => 3000],
            ['name' => 'Warna A3', 'category_id' => Category::where('name', 'photocopy')->first()->id, 'size' => 'A3', 'color_type' => 'warna', 'price' => 5000],

            // Cetak Foto
            ['name' => '4x6', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '4x6', 'price' => 2000],
            ['name' => '3x4', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '3x4', 'price' => 2000],
            ['name' => '2x3', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '2x3', 'price' => 2000],
            ['name' => '2R', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '2R', 'price' => 3000],
            ['name' => '3R', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '3R', 'price' => 4000],
            ['name' => '4R', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '4R', 'price' => 5000],
            ['name' => '5R', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '5R', 'price' => 6000],
            ['name' => '10R', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '10R', 'price' => 12000],
            ['name' => '12R', 'category_id' => Category::where('name', 'cetakfoto')->first()->id, 'size' => '12R', 'price' => 15000],

            // Printout
            ['name' => 'Hitam Putih A4', 'category_id' => Category::where('name', 'printout')->first()->id, 'size' => 'A4', 'color_type' => 'hitamputih', 'price' => 1000],
            ['name' => 'Hitam Putih F4', 'category_id' => Category::where('name', 'printout')->first()->id, 'size' => 'F4', 'color_type' => 'hitamputih', 'price' => 1000],
            ['name' => 'Hitam Putih A3', 'category_id' => Category::where('name', 'printout')->first()->id, 'size' => 'A3', 'color_type' => 'hitamputih', 'price' => 3000],
            ['name' => 'Warna A4', 'category_id' => Category::where('name', 'printout')->first()->id, 'size' => 'A4', 'color_type' => 'warna', 'price' => 3000],
            ['name' => 'Warna F4', 'category_id' => Category::where('name', 'printout')->first()->id, 'size' => 'F4', 'color_type' => 'warna', 'price' => 3000],
            ['name' => 'Warna A3', 'category_id' => Category::where('name', 'printout')->first()->id, 'size' => 'A3', 'color_type' => 'warna', 'price' => 5000],

            // Laminating
            ['name' => 'A3', 'category_id' => Category::where('name', 'laminating')->first()->id, 'size' => 'A3', 'price' => 1000],
            ['name' => 'A4', 'category_id' => Category::where('name', 'laminating')->first()->id, 'size' => 'A4', 'price' => 4000],

            // Jilid
            ['name' => 'Biasa', 'category_id' => Category::where('name', 'jilid')->first()->id, 'price' => 5000],
            ['name' => 'Spiral No4', 'category_id' => Category::where('name', 'jilid')->first()->id, 'price' => 6000],
            ['name' => 'Spiral No5', 'category_id' => Category::where('name', 'jilid')->first()->id, 'price' => 7000],
            ['name' => 'Spiral No6', 'category_id' => Category::where('name', 'jilid')->first()->id, 'price' => 8000],
            ['name' => 'Spiral No7', 'category_id' => Category::where('name', 'jilid')->first()->id, 'price' => 9000],
            ['name' => 'Spiral No8', 'category_id' => Category::where('name', 'jilid')->first()->id, 'price' => 10000],

            ['name' => 'Seragam SD', 'category_id' => Category::where('name', 'pakaian')->first()->id, 'price' => 40000],
            ['name' => 'Seragam SMP', 'category_id' => Category::where('name', 'pakaian')->first()->id, 'price' => 40000],

            ['name' => 'Pulpen Joyko', 'category_id' => Category::where('name', 'alat tulis')->first()->id, 'price' => 4000],
            ['name' => 'Pensil Joyko', 'category_id' => Category::where('name', 'alat tulis')->first()->id, 'price' => 4000],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
        
}
