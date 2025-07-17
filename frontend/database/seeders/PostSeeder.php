<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run()
    {
        // Standard post
        $post1 = Post::create([
            'title' => 'Belajar Dasar PHP untuk Pemula',
            'slug' => Str::slug('Belajar Dasar PHP untuk Pemula'),
            'excerpt' => 'Panduan singkat memulai belajar PHP untuk pemula di Pondok Koding.',
            'content' => 'PHP adalah bahasa scripting server-side yang banyak digunakan untuk pengembangan web. Di Pondok Koding, Anda akan mempelajari sintaks dasar, variabel, fungsi, dan konsep pemrograman PHP secara praktis.',
            'image' => 'assets/img/blog/blog01.jpg',
            'post_type' => 'standard',
            'video_link' => null,
            'author_id' => 1,
            'comment_count' => 0,
        ]);
        $post1->categories()->attach(Category::where('slug','php')->first());
        $post1->tags()->attach(Tag::where('slug','php')->first());

        // Gallery post
        $post2 = Post::create([
            'title' => 'Galeri Hasil Karya Siswa',
            'slug' => Str::slug('Galeri Hasil Karya Siswa'),
            'excerpt' => 'Lihat beberapa hasil karya kreatif dari siswa Pondok Koding.',
            'content' => 'Berikut adalah beberapa hasil proyek yang telah dibuat oleh siswa kami, mulai dari aplikasi sederhana, website responsif, hingga skrip PHP kompleks.',
            'image' => null,
            'post_type' => 'gallery',
            'video_link' => null,
            'author_id' => 1,
            'comment_count' => 0,
        ]);
        $post2->categories()->attach([Category::where('slug','javascript')->first()->id, Category::where('slug','html-css')->first()->id]);
        $post2->tags()->attach([Tag::where('slug','javascript')->first()->id, Tag::where('slug','html')->first()->id]);

        // Video post
        $post3 = Post::create([
            'title' => 'Tutorial Laravel Crash Course',
            'slug' => Str::slug('Tutorial Laravel Crash Course'),
            'excerpt' => 'Video tutorial singkat untuk membangun aplikasi dengan Laravel.',
            'content' => 'Dalam video ini, Anda akan belajar langkah-langkah dasar membuat project Laravel, routing, controller, hingga database migrations.',
            'image' => 'assets/img/blog/blog03.jpg',
            'post_type' => 'video',
            'video_link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'author_id' => 1,
            'comment_count' => 0,
        ]);
        $post3->categories()->attach(Category::where('slug','laravel')->first());
        $post3->tags()->attach(Tag::where('slug','laravel')->first());
    }
}
