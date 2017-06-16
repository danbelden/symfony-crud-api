<?php

namespace Tests\AppBundle\Paginator;

use AppBundle\Paginator\LimitOffsetHelper;
use PHPUnit\Framework\TestCase;

class LimitOffsetHelperTest extends TestCase
{
    public function dataProviderGetPreviousLimit()
    {
        return array(
            array(0, 1, 1, null),
            array(1, 0, 1, null),
            array(1, 1, 0, null),
            array(1, 1, 1, 1),
            array(1, 1, 2, 1),
            array(2, 1, 1, 1),
            array(2, 2, 1, 1),
            array(5, 2, 1, 1),
            array(2, 2, 2, 2),
            array(1, 2, 1, 1),
            array(4, 5, 10, 4),
            array(5, 3, 10, 3),
        );
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
        return array(
            array(0, 1, 1, null),
            array(1, 0, 1, null),
            array(1, 1, 0, null),
            array(1, 1, 1, 0),
            array(1, 1, 2, 0),
            array(2, 1, 1, 0),
            array(2, 2, 1, 0),
            array(5, 2, 1, 0),
            array(2, 2, 2, 0),
            array(1, 2, 1, 0),
            array(4, 5, 10, 1),
            array(5, 3, 10, 0),
        );
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
        return array(
            array(0, 1, 1, null),
            array(1, 0, 1, null),
            array(1, 1, 0, null),
            array(1, 1, 1, null),
            array(1, 1, 2, null),
            array(1, 1, 3, 1),
            array(2, 0, 1, null),
            array(2, 1, 1, null),
            array(2, 2, 2, null),
            array(2, 2, 3, null),
            array(2, 2, 4, null),
            array(2, 2, 5, 1),
            array(2, 2, 6, 2),
            array(1, 2, 1, null),
            array(1, 2, 2, null),
            array(1, 2, 3, null),
            array(1, 2, 4, 1),
            array(4, 0, 10, 4),
            array(4, 5, 10, 1),
            array(5, 3, 10, 2),
            array(5, 8, 10, null),
            array(5, 10, 10, null),
            array(5, 15, 10, null),
        );
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
        return array(
            array(0, 1, 1, null),
            array(1, 0, 1, null),
            array(1, 1, 0, null),
            array(1, 1, 1, null),
            array(1, 1, 2, null),
            array(1, 1, 3, 2),
            array(2, 0, 1, null),
            array(2, 1, 1, null),
            array(2, 2, 2, null),
            array(2, 2, 3, null),
            array(2, 2, 4, null),
            array(2, 2, 5, 4),
            array(2, 2, 6, 4),
            array(1, 2, 1, null),
            array(1, 2, 2, null),
            array(1, 2, 3, null),
            array(1, 2, 4, 3),
            array(4, 0, 10, 4),
            array(4, 5, 10, 9),
            array(5, 3, 10, 8),
            array(5, 8, 10, null),
            array(5, 10, 10, null),
            array(5, 15, 10, null),
        );
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
