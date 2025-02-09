<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Work'],
            ['name' => 'Personal'],
            ['name' => 'Shopping'],
            ['name' => 'Health'],
            ['name' => 'Fitness'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
