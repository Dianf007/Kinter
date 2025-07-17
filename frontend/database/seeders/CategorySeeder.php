<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'VueJS', 'slug' => 'vuejs'],
            ['name' => 'ReactJS', 'slug' => 'reactjs'],
            ['name' => 'HTML & CSS', 'slug' => 'html-css'],
            ['name' => 'Database', 'slug' => 'database'],
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
