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

    public function getNextBadge($achievementsCount)
    {
        $badge = Badge::where('achievements_count_needed', '>', $achievementsCount)->orderBy('id', 'asc')->first();
        if(!isset($badge)) $badge = Badge::orderBy('id', 'desc')->first();
        return $badge;
    }

    public function getUnlockedAchievements(User $user)
    {
        $unlockAchievements = [];
        $achievements = Achievement::where(function ($query) use ($user) {
            $query->where('type', AchievementTypeDefinition::COMMENT)->where('point', '<=', $user->comments()->count());
        })->orWhere(function ($query) use ($user) {
            $query->where('type', AchievementTypeDefinition::LESSON)->where('point', '<=', $user->watched()->count());
        })->get();

        foreach ($achievements as $achievement) {
            $unlockAchievements[] = $achievement->name;
        }

        return $unlockAchievements;
    }

    public function getNextAvailableAchievements(User $user)
    {
        $nextAvailableAchievements = [];

        $achievement = Achievement::where('type', AchievementTypeDefinition::COMMENT)->where('point', '>', $user->comments()->count())->orderBy('id', 'asc')->first();
        if($achievement){
            $nextAvailableAchievements[] = $achievement->name;
        }

        $achievement = Achievement::where('type', AchievementTypeDefinition::LESSON)->where('point', '>', $user->watched()->count())->orderBy('id', 'asc')->first();
        if($achievement){
            $nextAvailableAchievements[] = $achievement->name;
        }
      
        return $nextAvailableAchievements;
    }
}
