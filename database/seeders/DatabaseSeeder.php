<?php

namespace Database\Seeders;

use App\Models\ContosCategorias;
use App\Models\States;
use App\Models\Cities;
use App\Models\Procura;
use App\Models\Hobby;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        $this->call(ContosCategoriasSeeder::class);         
         $this->call(StatesTableSeeder::class);
         $this->call(CitiesTableSeeder::class);
         $this->call(ProcuraSeeder::class);
         $this->call(HobbySeeder::class);
    }
}
