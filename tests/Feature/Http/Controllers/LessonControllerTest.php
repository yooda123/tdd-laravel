<?php

namespace Tests\Feature\Http;

use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response as HttpResponse;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @dataProvider dataTestShow
     */
    public function testShow($capacity, $reservationCount, $expectedVacancyLevel)
    {
        // $capacity = 1;
        // $reservationCount = 1;
        // $expectedVacancyLevel = "×";

        // $response = $this->get('/lessons/1');
        $lesson = factory(Lesson::class)->create([
                'name'=>'楽しいヨガレッスン',
                'capacity'=>$capacity,
        ]);

        for ($i=0; $i<$reservationCount; $i++) {
            $user = factory(User::class)->create();
            // $reservation = factory(Reservation::class)->create([
            //     'lesson_id' => $lesson->id,
            //     'user_id' => $user->id,
            // ]);
            $lesson->reservations()->save(factory(Reservation::class)->make([
                'user_id' => $user->id,
            ]));
        }

        $response = $this->get("/lessons/{$lesson->id}");
    //     // $response = $this->get('/lessons/{$lesson->id}');

        $response->assertStatus(200);
    //     // $response->assertSee('楽しいヨガレッスン');
    //     // $response->assertSee('◎');
        $response->assertSee($lesson->name);
        $response->assertSee("空き状況: {$expectedVacancyLevel}");
    }

    public function dataTestShow()
    {
        return [
            '空きなし' => [
                'capacity' => 1,
                'reservationCount' => 1,
                'expectedVacancyLevel' => "×",
            ],
            '空きわずか1' =>
            [
                'capacity' => 1,
                'reservationCount' => 0,
                'expectedVacancyLevel' => "△",
            ],
            '空きわずか2' =>
            [
                'capacity' => 10,
                'reservationCount' => 6,
                'expectedVacancyLevel' => "△",
            ],
            '空き十分' =>
            [
                'capacity' => 10,
                'reservationCount' => 5,
                'expectedVacancyLevel' => "◎",
            ],

        ];
    }
}
