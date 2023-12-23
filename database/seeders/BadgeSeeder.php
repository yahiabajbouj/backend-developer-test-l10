<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::insert([
            ["name"=> "Beginner","achievements_count_needed" => 0],
            ["name"=> "Intermediate","achievements_count_needed" => 4],
            ["name"=> "Advanced","achievements_count_needed" => 8],
            ["name"=> "Master","achievements_count_needed" => 10]
        ]);
    }
}
