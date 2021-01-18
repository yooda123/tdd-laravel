<?php

namespace Tests\Unit\Models;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider dataTestCanReserve_OK
     */
    public function testCanReserve_OK(
        $plan,
        $remainingCount,
        $reservationCount)
    {
        // $user = factory(User::class)->create([
        //     'plan'=> $plan,
        // ]);

        // レッスン残り枠数
        // $remainingCount = 0;

        // 当該ユーザの当月予約数
        // $reservationCount = 0;

        /** @var User $user */
        $user = Mockery::mock(User::class)->makePartial(); //★⇒$user->canReserve()は実メソッドを呼ぶようにするため
        $user->shouldReceive('reservationCountThisMonth')->andReturn($reservationCount);
        // $user = new User();
        $user->plan = $plan;

        /** @var Lesson $lesson */
        $lesson = Mockery::mock(Lesson::class);
        $lesson->shouldReceive('getVacantCount')->andReturn($remainingCount);
        // $this->assertSame($expected, $user->canReserve($remainingCount, $reservationCount));

        // $this->assertSame($expected, $user->canReserve($remainingCount, $reservationCount));
        // $this->assertSame($expected, $user->canReserve($lesson, $reservationCount));
        // $this->assertTrue($user->canReserve($lesson));
        $user->canReserve($lesson);
        $this->assertTrue(true);
    }

    public function dataTestCanReserve_OK()
    {
        return [
            '予約可:レギュラー, 空きあり, 月の上限以下' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 4,
                // 'expected' => true,
            ],
            '予約可:ゴールド, 空きあり, 月の上限以下' => [
                'plan' => 'gold',
                'remainingCount' => 1,
                'reservationCount' => 4,
                // 'expected' => true,
            ],
            '予約不可:ゴールド, 空きあり, 月の上限' => [
                'plan' => 'gold',
                'remainingCount' => 1,
                'reservationCount' => 5,
                // 'errorMessage' => true,
            ],
        ];
    }

    /**
     * @dataProvider dataTestCanReserve_NG
     */
    public function testCanReserve_NG(
        string $plan,
        int $remainingCount,
        int $reservationCount,
        string $errorMessage)
    {
        /** @var User $user */
        $user = Mockery::mock(User::class)->makePartial(); //★⇒$user->canReserve()は実メソッドを呼ぶようにするため
        $user->shouldReceive('reservationCountThisMonth')->andReturn($reservationCount);
        // $user = new User();
        $user->plan = $plan;

        /** @var Lesson $lesson */
        $lesson = Mockery::mock(Lesson::class);
        $lesson->shouldReceive('getVacantCount')->andReturn($remainingCount);

        $this->expectExceptionMessage($errorMessage);
        $user->canReserve($lesson);
    }

    public function dataTestCanReserve_NG()
    {
        return [
            '予約不可:レギュラー, 空きなし, 月の上限以下' => [
                'plan' => 'regular',
                'remainingCount' => 0,
                'reservationCount' => 4,
                'errorMessage' => 'レッスンの予約可能上限に達しています',
            ],
            '予約不可:レギュラー, 空きあり, 月の上限' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'errorMessage' => '今月の予約がプランの上限に達しています',
            ],
            '予約不可:ゴールド, 空きなし, 月の上限以下' => [
                'plan' => 'gold',
                'remainingCount' => 0,
                'reservationCount' => 4,
                'errorMessage' => 'レッスンの予約可能上限に達しています',
            ],
        ];
    }

    public function testCanReserve2() {
        $user = new User();
        $user->plan = 'regular';

        /** @var Lesson $lesson */
        $lesson = Mockery::mock(Lesson::class);
        $lesson->shouldReceive('isVacant')->andReturn(true);

        $this->assertSame(true, $user->canReserve2($lesson));

    }


}
