<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'VueJS', 'slug' => 'vuejs'],
            ['name' => 'ReactJS', 'slug' => 'reactjs'],
            ['name' => 'CSS', 'slug' => 'css'],
            ['name' => 'HTML', 'slug' => 'html'],
            ['name' => 'MySQL', 'slug' => 'mysql'],
        ];
        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
