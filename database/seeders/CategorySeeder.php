<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'photocopy'],
            ['name' => 'cetakfoto'],
            ['name' => 'printout'],
            ['name' => 'laminating'],
            ['name' => 'jilid'],
            ['name' => 'pakaian'],
            ['name' => 'alat tulis'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
