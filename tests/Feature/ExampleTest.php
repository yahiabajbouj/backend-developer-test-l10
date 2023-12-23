<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 20; $i++) {
            $comment = Comment::factory()->create();
            $comment->update(["user_id" => $user->id]);
            event(new CommentWritten($comment));
        }

        for ($i = 1; $i <= 50; $i++) {
            $lesson = Lesson::find($i);
            event(new LessonWatched($lesson, $user));
        }

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $json) =>
            $json->where('current_badge', 'Master') // because the customer makes all achievements depending on the actions above
                ->hasAll(['unlocked_achievements', 'next_available_achievements', 'current_badge', 'next_badge', 'remaing_to_unlock_next_badge'])
                ->where('unlocked_achievements', ["First Comment Written","3 Comments Written","5 Comments Written","10 Comments Written","20 Comments Written","First Lesson Watched","5 Lessons Watched","10 Lessons Watched","25 Lessons Watched","50 Lessons Watched"])
                ->where('next_available_achievements', [])
                ->etc()
        );
    }

    // in case don't compleat achievements to check [next_available_achievements, next_badge, remaing_to_unlock_next_badge]
    public function test_the_application_returns_a_successful_response_2(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $comment = Comment::factory()->create();
            $comment->update(["user_id" => $user->id]);
            event(new CommentWritten($comment));
        }

        for ($i = 1; $i <= 5; $i++) {
            $lesson = Lesson::find($i);
            event(new LessonWatched($lesson, $user));
        }

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $json) =>
            $json->where('current_badge', 'Intermediate') // because the customer makes 4 achievements depending on the actions above
                ->where('next_badge', 'Advanced')
                ->where('remaing_to_unlock_next_badge', 4) // because the customer need 4 achievements to become Advanced depending on the actions above
                ->hasAll(['unlocked_achievements', 'next_available_achievements', 'current_badge', 'next_badge', 'remaing_to_unlock_next_badge'])
                ->where('unlocked_achievements', ["First Comment Written","3 Comments Written","First Lesson Watched","5 Lessons Watched"])
                ->where('next_available_achievements', ["5 Comments Written","10 Lessons Watched"])
                ->etc()
        );
    }
}
