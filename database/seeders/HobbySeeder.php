<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hobby;

class HobbySeeder extends Seeder
{
    public function run(): void
    {
        $hobbies = [
            ['nome' => 'Lingeries'],
            ['nome' => 'Podolatria'],
            ['nome' => 'Troca de casais'],
            ['nome' => 'Menage Feminino'],
            ['nome' => 'Sadomasoquismo'],
            ['nome' => 'Menage Masculino'],
            ['nome' => 'Voyeurismo'],
            ['nome' => 'Sexo às Escuras'],
            ['nome' => 'Sexo Virtual']
        ];

        foreach ($hobbies as $hobby) {
            Hobby::create($hobby);
        }
    }
}