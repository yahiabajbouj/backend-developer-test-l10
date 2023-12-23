<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Http\Definition\AchievementTypeDefinition;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WrittenComment
{
    /**
     * Create the event listener.
    */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $userCommentAchievementsCount = $event->comment->user->comments()->count();
        if (in_array($userCommentAchievementsCount, Achievement::where('type', AchievementTypeDefinition::COMMENT)->pluck('point')->toArray())) {
            $achievement_name = trans_choice('general.achievement_title.comment', $userCommentAchievementsCount, ["value" => $userCommentAchievementsCount]);
            AchievementUnlocked::dispatch($achievement_name, $event->comment->user);
        }
    }
}
