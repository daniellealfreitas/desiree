<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Curitiba como ponto central
        $baseLat = -25.4297;
        $baseLng = -49.2719;

        // Usuário fixo para login
        User::create([
            'name' => 'Usuário Teste',
            'username'=> 'testuser22',
            'email' => 'teste22@teste.com',
            'password' => Hash::make('12345678'),
            'latitude' => $baseLat,
            'longitude' => $baseLng,
            
        ]);

        // Gerar mais 10 usuários próximos
        for ($i = 1; $i <= 10; $i++) {
            $lat = $baseLat + fake()->randomFloat(7, -0.005, 0.005);
            $lng = $baseLng + fake()->randomFloat(7, -0.005, 0.005);

            User::create([
                'name' => "Usuário $i",
                'username' => fake()->unique()->userName(),
                'email' => "usuario{$i}@teste.com",
                'password' => Hash::make('12345678'),
                'latitude' => $lat,
                'longitude' => $lng,
                
            ]);
        }
    }
}
