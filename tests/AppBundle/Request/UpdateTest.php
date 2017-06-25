<?php

namespace Tests\AppBundle\Request;

use AppBundle\Request\Update as UpdateRequest;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    public function testHasPublicNameProperty()
    {
        $request = new UpdateRequest();
        $this->assertNull($request->name);

        $request->name = 'Test';
        $this->assertSame('Test', $request->name);
    }
}
