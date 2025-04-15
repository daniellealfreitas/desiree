<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContosCategoria;

class ContosCategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Romance',
                'description' => 'Contos românticos e histórias de amor',
                'slug' => 'romance'
            ],
            [
                'name' => 'Erótico',
                'description' => 'Contos com conteúdo erótico e sensual',
                'slug' => 'erotico'
            ],
            [
                'name' => 'Fantasia',
                'description' => 'Histórias que envolvem elementos fantásticos',
                'slug' => 'fantasia'
            ],
            [
                'name' => 'Aventura',
                'description' => 'Contos de aventura e ação',
                'slug' => 'aventura'
            ],
            [
                'name' => 'Drama',
                'description' => 'Histórias dramáticas e emocionantes',
                'slug' => 'drama'
            ]
        ];

        foreach ($categories as $category) {
            ContosCategoria::create($category);
        }
    }
}