<?php

namespace Tests\AppBundle\Request;

use AppBundle\Request\ReadMany as ReadManyRequest;
use PHPUnit\Framework\TestCase;

class ReadManyTest extends TestCase
{
    public static $properties = [
        'name',
        'limit',
        'offset',
        'sortField',
        'sortDirection'
    ];

    public function testHasPublicProperties()
    {
        $request = new ReadManyRequest();
        $publicProps = array_keys(get_object_vars($request));
        $expectedProps = self::$properties;

        sort($publicProps);
        sort($expectedProps);

        $this->assertSame($expectedProps, $publicProps);
    }

    public function testEachPropertyCanBeSetAndGet()
    {
        $request = new ReadManyRequest();

        foreach (self::$properties as $propName) {
            $this->assertNull($request->{$propName});

            $request->{$propName} = 'Test';
            $this->assertSame('Test', $request->{$propName});
        }
    }
}
