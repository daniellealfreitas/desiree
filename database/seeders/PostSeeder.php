<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        for ($i = 0; $i < 20; $i++) {
            Post::create([
                'user_id' => $users->random()->id,
                'content' => fake()->paragraph(3),
                'likes_count' => fake()->numberBetween(0, 1000),
                'image' => fake()->boolean(30) ? fake()->imageUrl() : null,
                'video' => fake()->boolean(20) ? 'example-video-' . fake()->numberBetween(1, 5) . '.mp4' : null,
                'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }
    }
}
