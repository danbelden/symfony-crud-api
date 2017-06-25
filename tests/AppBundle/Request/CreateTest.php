<?php

namespace Tests\AppBundle\Request;

use AppBundle\Request\Create as CreateRequest;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testHasPublicNameProperty()
    {
        $request = new CreateRequest();
        $this->assertNull($request->name);

        $request->name = 'Test';
        $this->assertSame('Test', $request->name);
    }
}
