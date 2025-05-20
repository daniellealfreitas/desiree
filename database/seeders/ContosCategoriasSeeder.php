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
                'title' => 'Romance',
                'description' => 'Contos românticos e histórias de amor',
                
            ],
            [
                'title' => 'Erótico',
                'description' => 'Contos com conteúdo erótico e sensual',
                
            ],
            [
                'title' => 'Fantasia',
                'description' => 'Histórias que envolvem elementos fantásticos',
                
            ],
            [
                'title' => 'Aventura',
                'description' => 'Contos de aventura e ação',
                
            ],
            [
                'title' => 'Drama',
                'description' => 'Histórias dramáticas e emocionantes',
                
            ]
        ];

        foreach ($categories as $category) {
            ContosCategoria::create($category);
        }
    }
}