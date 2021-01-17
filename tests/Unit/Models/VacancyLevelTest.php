<?php

namespace Tests\Unit\Models;

use App\Models\VacancyLevel;
use PHPUnit\Framework\TestCase;

class VacancyLevelTest extends TestCase
{
    /**
     * @dataProvider dataMark
     */
    public function testmark(int $vacantCount, string $expected)
    {
        // $level = new VacancyLevel(0);
        // $this->assertSame('×', $level->mark());

        // $level = new VacancyLevel(1);
        // $this->assertSame('△', $level->mark());

        // $level = new VacancyLevel(4);
        // $this->assertSame('△', $level->mark());

        // $level = new VacancyLevel(5);
        // $this->assertSame('◎', $level->mark());

        // $level = new VacancyLevel(6);
        // $this->assertSame('◎', $level->mark());

        $level = new VacancyLevel($vacantCount);
        $this->assertSame($expected, $level->mark());
    }

    public function dataMark() {
        return [
            '空きなし' => [
                'vacantCount' => 0,
                'expected' => '×',
            ],
            '残りわずか1' => [
                'vacantCount' => 1,
                'expected' => '△',
            ],
            '残りわずか2' => [
                'vacantCount' => 4,
                'expected' => '△',
            ],
            '空きあり1' => [
                'vacantCount' => 5,
                'expected' => '◎',
            ],
            '空きあり2' => [
                'vacantCount' => 6,
                'expected' => '◎',
            ],
        ];
    }

    /**
     * @dataProvider dataSlug
     */
    public function testSlug(int $vacantCount, string $expected)
    {
        $level = new VacancyLevel($vacantCount);
        $this->assertSame($expected, $level->slug());
    }

    public function dataSlug()
    {
        return [
            '空きなし' => [
                'vacantCount' => 0,
                'expected' => 'empty',
            ],
            '残りわずか' => [
                'vacantCount' => 1,
                'expected' => 'few',
            ],
            '空き十分' => [
                'vacantCount' => 5,
                'expected' => 'enough',
            ],
        ];
    }

}
