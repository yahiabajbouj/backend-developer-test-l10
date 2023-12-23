<?php

namespace Database\Seeders;

use App\Http\Definition\AchievementTypeDefinition;
use App\Models\Achievement;
use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commentAchievementPoints = config('general.comment_achievement_point');
        foreach ($commentAchievementPoints as $point) {
            Achievement::create(["point" => $point, "type" => AchievementTypeDefinition::COMMENT]);
        }

        $lessonAchievementPoint = config('general.lesson_achievement_point');
        foreach ($lessonAchievementPoint as $point) {
            Achievement::create(["point" => $point, "type" => AchievementTypeDefinition::LESSON]);
        }
    }
}
