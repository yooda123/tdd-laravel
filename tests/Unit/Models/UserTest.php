<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * @dataProvider dataTestCanReserve
     */
    public function testCanReserve(
        $plan,
        $remainingCount,
        $reservationCount,
        $expected)
    {

        $user = new User();
        $user->plan = $plan;

        // $user = factory(User::class)->create([
        //     'plan'=> $plan,
        // ]);


        // レッスン残り枠数
        // $remainingCount = 0;

        // 当該ユーザの当月予約数
        // $reservationCount = 0;

        $this->assertSame($expected, $user->canReserve($remainingCount, $reservationCount));
    }

    public function dataTestCanReserve()
    {
        return [
            '予約不可:レギュラー, 空きなし, 月の上限以下' => [
                'plan' => 'regular',
                'remainingCount' => 0,
                'reservationCount' => 4,
                'expected' => false,
            ],
            '予約不可:レギュラー, 空きあり, 月の上限' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'expected' => false,
            ],
            '予約可:レギュラー, 空きあり, 月の上限以下' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 4,
                'expected' => true,
            ],
            '予約不可:ゴールド, 空きなし, 月の上限以下' => [
                'plan' => 'gold',
                'remainingCount' => 0,
                'reservationCount' => 4,
                'expected' => false,
            ],
            '予約不可:ゴールド, 空きあり, 月の上限' => [
                'plan' => 'gold',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'expected' => true,
            ],
            '予約可:ゴールド, 空きあり, 月の上限以下' => [
                'plan' => 'gold',
                'remainingCount' => 1,
                'reservationCount' => 4,
                'expected' => true,
            ],
        ];
    }
}
