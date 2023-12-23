<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Http\Services\LessonService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WatchedLesson
{
    /**
     * Create the event listener.
     */

    public $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }


    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $this->lessonService->checkTheNewLessonAchievement($event->user, $event->lesson);
    }
}
