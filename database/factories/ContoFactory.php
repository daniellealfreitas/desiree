<?php
namespace Database\Factories;

use App\Models\Conto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContoFactory extends Factory
{
    protected $model = Conto::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence,
            'categoria' => $this->faker->randomElement(['Erotismo', 'Fetiches', 'Gays']),
            'anonimo' => $this->faker->boolean,
            'conteudo' => $this->faker->paragraph,
            'user_id' => User::factory(),
        ];
    }
}
