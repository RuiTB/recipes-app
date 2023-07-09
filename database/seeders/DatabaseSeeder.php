<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        // $user = \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Bookmark::factory(10)->create();
        Recipe::factory(25)->create();
        Ingredient::factory(50)->create();
        Comment::factory(50)->create();


        for ($i = 0; $i < rand(10, 25); $i++) {
            Recipe::inRandomOrder()->first()->ingredients()->syncWithoutDetaching(Ingredient::inRandomOrder()->first());
        }

        for ($i = 0; $i < rand(10, 25); $i++) {
            Bookmark::inRandomOrder()->first()->recipes()->syncWithoutDetaching(Recipe::inRandomOrder()->first());
        }

        for ($i = 0; $i < rand(15, 25); $i++) {
            User::inRandomOrder()->first()->likes()->syncWithoutDetaching(Recipe::inRandomOrder()->first());
        }

        for ($i = 0; $i < rand(10, 25); $i++) {
            do {
                $user = User::inRandomOrder()->first();
                $user2 = User::inRandomOrder()->first();
            } while ($user->id === $user2->id);

            $user->follows()->syncWithoutDetaching($user2);
        }
    }
}
