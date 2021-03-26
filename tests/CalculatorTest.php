<?php

declare(strict_types=1);

namespace Tests;

use App\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param string $expression
     * @param int $expected
     */
    public function testCalculate(string $expression, int $expected): void
    {
        $calculator = new Calculator();

        $this->assertEquals($expected, $calculator->calculate($expression));
    }

    public function dataProvider(): array
    {
        return [
//            '1 + 1' => [
//                'expression' => '1 + 1',
//                'expected' => 2,
//            ],
//            '1 + 1 - 3' => [
//                'expression' => '1 + 1 - 3',
//                'expected' => -1,
//            ],
//            '1 + 1 * 4' => [
//                'expression' => '1 + 1 * 4',
//                'expected' => 5,
//            ],
            '1 + 2 * (3 + 4 / 2 - (1 + 2)) * 2 + 1' => [
                'expression' => '1 + 2 * (3 + 4 / 2 - (1 + 2)) * 2 + 1',
                'expected' => 10,
            ],
        ];
    }
}
