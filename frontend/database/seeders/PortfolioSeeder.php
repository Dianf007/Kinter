<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $items = [
            [
                'title' => 'Creative Drawing',
                'image' => 'assets/img/portfolio/port01.jpg',
                'categories' => 'cat1 cat2',
                'link' => '#',
                'author' => 'Pondok Koding',
            ],
            [
                'title' => 'Kids Art Class',
                'image' => 'assets/img/portfolio/port02.jpg',
                'categories' => 'cat2 cat4',
                'link' => '#',
                'author' => 'Pondok Koding',
            ],
            [
                'title' => 'Colorful Painting',
                'image' => 'assets/img/portfolio/port03.jpg',
                'categories' => 'cat3 cat1',
                'link' => '#',
                'author' => 'Pondok Koding',
            ],
            [
                'title' => 'Handcraft Workshop',
                'image' => 'assets/img/portfolio/port04.jpg',
                'categories' => 'cat1 cat4',
                'link' => '#',
                'author' => 'Pondok Koding',
            ],
            [
                'title' => 'Imagination Class',
                'image' => 'assets/img/portfolio/port05.jpg',
                'categories' => 'cat3 cat4',
                'link' => '#',
                'author' => 'Pondok Koding',
            ],
            [
                'title' => 'Fun with Colors',
                'image' => 'assets/img/portfolio/port06.jpg',
                'categories' => 'cat2 cat3',
                'link' => '#',
                'author' => 'Pondok Koding',
            ],
        ];

        foreach ($items as $item) {
            Portfolio::create($item);
        }
    }
}
