<?php

namespace Tests\AppBundle\Paginator;

use AppBundle\Paginator\LimitOffsetHelper;
use PHPUnit\Framework\TestCase;

class LimitOffsetHelperTest extends TestCase
{
    public function dataProviderGetPreviousLimit()
    {
        return [
            [0, 1, 1, null],
            [1, 0, 1, null],
            [1, 1, 0, null],
            [1, 1, 1, 1],
            [1, 1, 2, 1],
            [2, 1, 1, 1],
            [2, 2, 1, 1],
            [5, 2, 1, 1],
            [2, 2, 2, 2],
            [1, 2, 1, 1],
            [4, 5, 10, 4],
            [5, 3, 10, 3],
        ];
    }

    /**
     * @dataProvider dataProviderGetPreviousLimit
     * @param mixed $limit
     * @param mixed $offset
     * @param mixed $count
     * @param mixed $expected
     */
    public function testGetPreviousLimit($limit, $offset, $count, $expected)
    {
        $this->assertSame(
            $expected,
            LimitOffsetHelper::getPreviousLimit($limit, $offset, $count)
        );
    }

    public function dataProviderGetPreviousOffset()
    {
        return [
            [0, 1, 1, null],
            [1, 0, 1, null],
            [1, 1, 0, null],
            [1, 1, 1, 0],
            [1, 1, 2, 0],
            [2, 1, 1, 0],
            [2, 2, 1, 0],
            [5, 2, 1, 0],
            [2, 2, 2, 0],
            [1, 2, 1, 0],
            [4, 5, 10, 1],
            [5, 3, 10, 0],
        ];
    }

    /**
     * @dataProvider dataProviderGetPreviousOffset
     * @param mixed $limit
     * @param mixed $offset
     * @param mixed $count
     * @param mixed $expected
     */
    public function testGetPreviousOffset($limit, $offset, $count, $expected)
    {
        $this->assertSame(
            $expected,
            LimitOffsetHelper::getPreviousOffset($limit, $offset, $count)
        );
    }

    public function dataProviderGetNextLimit()
    {
        return [
            [0, 1, 1, null],
            [1, 0, 1, null],
            [1, 1, 0, null],
            [1, 1, 1, null],
            [1, 1, 2, null],
            [1, 1, 3, 1],
            [2, 0, 1, null],
            [2, 1, 1, null],
            [2, 2, 2, null],
            [2, 2, 3, null],
            [2, 2, 4, null],
            [2, 2, 5, 1],
            [2, 2, 6, 2],
            [1, 2, 1, null],
            [1, 2, 2, null],
            [1, 2, 3, null],
            [1, 2, 4, 1],
            [4, 0, 10, 4],
            [4, 5, 10, 1],
            [5, 3, 10, 2],
            [5, 8, 10, null],
            [5, 10, 10, null],
            [5, 15, 10, null],
        ];
    }

    /**
     * @dataProvider dataProviderGetNextLimit
     * @param mixed $limit
     * @param mixed $offset
     * @param mixed $count
     * @param mixed $expected
     */
    public function testGetNextLimit($limit, $offset, $count, $expected)
    {
        $this->assertSame(
            $expected,
            LimitOffsetHelper::getNextLimit($limit, $offset, $count)
        );
    }

    public function dataProviderGetNextOffset()
    {
        return [
            [0, 1, 1, null],
            [1, 0, 1, null],
            [1, 1, 0, null],
            [1, 1, 1, null],
            [1, 1, 2, null],
            [1, 1, 3, 2],
            [2, 0, 1, null],
            [2, 1, 1, null],
            [2, 2, 2, null],
            [2, 2, 3, null],
            [2, 2, 4, null],
            [2, 2, 5, 4],
            [2, 2, 6, 4],
            [1, 2, 1, null],
            [1, 2, 2, null],
            [1, 2, 3, null],
            [1, 2, 4, 3],
            [4, 0, 10, 4],
            [4, 5, 10, 9],
            [5, 3, 10, 8],
            [5, 8, 10, null],
            [5, 10, 10, null],
            [5, 15, 10, null],
        ];
    }

    /**
     * @dataProvider dataProviderGetNextOffset
     * @param mixed $limit
     * @param mixed $offset
     * @param mixed $count
     * @param mixed $expected
     */
    public function testGetNextOffset($limit, $offset, $count, $expected)
    {
        $this->assertSame(
            $expected,
            LimitOffsetHelper::getNextOffset($limit, $offset, $count)
        );
    }
}
