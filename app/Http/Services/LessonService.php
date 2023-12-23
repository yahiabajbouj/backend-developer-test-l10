<?php

namespace App\Http\Services;

use App\Events\AchievementUnlocked;
use App\Http\Definition\AchievementTypeDefinition;
use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;

class LessonService
{
    public function checkTheNewLessonAchievement(User $user, Lesson $lesson)
    {
        $this->makeLessonAsWatchedForSpecificUser($user, $lesson);

        $countOfWatchedLesson = $user->watched()->count();
        if (in_array($countOfWatchedLesson, Achievement::where('type', AchievementTypeDefinition::LESSON)->pluck('point')->toArray())) {
            $achievement_name = trans_choice('general.achievement_title.lesson', $countOfWatchedLesson, ["value" => $countOfWatchedLesson]);
            AchievementUnlocked::dispatch($achievement_name, $user);
        }
    }

    public function makeLessonAsWatchedForSpecificUser(User $user, Lesson $lesson)
    {
        return LessonUser::firstOrCreate([
            "lesson_id" => $lesson->id,
            "user_id" => $user->id,
            "watched" => true
        ]);
    }
}
