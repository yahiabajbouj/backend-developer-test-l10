<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Http\Services\UserService;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AchievementUnlockedListener
{
    /**
     * Create the event listener.
     */

    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): void
    {
        $achievementsCount = $this->userService->getAchievementsCount($event->user);

        if (in_array($achievementsCount, Badge::pluck('achievements_count_needed')->toArray())) {
            $badge_name = $this->userService->getCurrentBadgeName($achievementsCount);
            BadgeUnlocked::dispatch($badge_name, $event->user);
        }
    }
}
