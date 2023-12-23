<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(User $user)
    {
        $achievementsCount = $this->userService->getAchievementsCount($user);
        $badgeName = $this->userService->getCurrentBadgeName($achievementsCount);
        $nextBadge = $this->userService->getNextBadge($achievementsCount);

        return response()->json([
            'unlocked_achievements' => $this->userService->getUnlockedAchievements($user),
            'next_available_achievements' => $this->userService->getNextAvailableAchievements($user),
            'current_badge' => $badgeName,
            'next_badge' => $nextBadge->name,
            'remaing_to_unlock_next_badge' => $nextBadge->achievements_count_needed - $achievementsCount
        ]);
    }
}
