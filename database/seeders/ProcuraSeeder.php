<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Procura;

class ProcuraSeeder extends Seeder
{
    public function run(): void
    {
        $procuras = [
            ['nome' => 'Casal'],
            ['nome' => 'Homem'],
            ['nome' => 'Mulher'],
            ['nome' => '18 a 25 Anos'],
            ['nome' => '25 a 35 Anos'],
            ['nome' => '35 a 45 Anos'],
            ['nome' => 'Mais de 45 Anos'],
            ['nome' => 'Casal com Homem'],
            ['nome' => 'Casal com Mulher'],
            ['nome' => 'Homem com Homem'],
            ['nome' => 'Homem com Mulher'],
            ['nome' => 'Mulher com Mulher'],
            ['nome' => 'Mulher com Homem'],
            ['nome' => 'Casal com Casal'],
            ['nome' => 'Casal com Grupo'],
            ['nome' => 'Grupo com Grupo'],
            ['nome' => 'Grupo com Casal'],
            ['nome' => 'Grupo com Homem'],
            ['nome' => 'Grupo com Mulher']
        ];

        foreach ($procuras as $procura) {
            Procura::create($procura);
        }
    }
}