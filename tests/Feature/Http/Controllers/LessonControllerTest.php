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
    public function testShow(int $capacity, int $reservationCount, string $expectedVacancyLevel, string $button)
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

        // 1. ページが正しく表示できること
        $response->assertStatus(HttpResponse::HTTP_OK);
    //     // $response->assertSee('楽しいヨガレッスン');
    //     // $response->assertSee('◎');

        // 2. 空き状況が正しく表示できること
        $response->assertSee($lesson->name);
        $response->assertSee("空き状況: {$expectedVacancyLevel}");

        // 3. 予約不可のときは予約できないこと（予約ボタンを押せない）
        // 4. 予約可のときは予約できること（予約ボタンを押せる）
        // $response->assertSee('<span class="btn btn-primary" disabled>予約できません</span>', false);
        // $response->assertSee('<button class="btn btn-primary">このレッスンを予約する</button>', false);
        $response->assertSee($button, false);

    }

    public function dataTestShow()
    {
        return [
            '空きなし' => [
                'capacity' => 1,
                'reservationCount' => 1,
                'expectedVacancyLevel' => "×",
                'button' => '<span class="btn btn-primary" disabled>予約できません</span>',
            ],
            '空きわずか1' =>
            [
                'capacity' => 1,
                'reservationCount' => 0,
                'expectedVacancyLevel' => "△",
                'button' => '<button class="btn btn-primary">このレッスンを予約する</button>',
            ],
            '空きわずか2' =>
            [
                'capacity' => 10,
                'reservationCount' => 6,
                'expectedVacancyLevel' => "△",
                'button' => '<button class="btn btn-primary">このレッスンを予約する</button>',
            ],
            '空き十分' =>
            [
                'capacity' => 10,
                'reservationCount' => 5,
                'expectedVacancyLevel' => "◎",
                'button' => '<button class="btn btn-primary">このレッスンを予約する</button>',
            ],

        ];
    }
}
