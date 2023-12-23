<?php

namespace App\Http\Services;

use App\Http\Definition\AchievementTypeDefinition;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;

class UserService
{
    public function getAchievementsCount(User $user)
    {
        return Achievement::where(function ($query) use ($user) {
            $query->where('type', AchievementTypeDefinition::COMMENT)->where('point', '<=', $user->comments()->count());
        })->orWhere(function ($query) use ($user) {
            $query->where('type', AchievementTypeDefinition::LESSON)->where('point', '<=', $user->watched()->count());
        })->count();
    }

    public function getCurrentBadgeName($achievementsCount)
    {
        return Badge::where('achievements_count_needed', '<=', $achievementsCount)->orderBy('id', 'desc')->first()->name;
    }
}
