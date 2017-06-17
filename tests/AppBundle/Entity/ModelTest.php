<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Model;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ModelTest extends TestCase
{
    public function testUuidGetAndSet()
    {
        $model = new Model();

        $defaultUuid = $model->getUuId();
        $this->assertNull($defaultUuid);

        $newUuid = Uuid::uuid4();
        $newUuidString = $newUuid->toString();

        $model->setUuid($newUuidString);
        $updatedUuid = $model->getUuId();
        $this->assertSame($newUuidString, $updatedUuid);
    }

    public function testNameGetAndSet()
    {
        $model = new Model();

        $defaultName = $model->getName();
        $this->assertNull($defaultName);

        $newName = 'test';

        $model->setName($newName);
        $updatedName = $model->getName();
        $this->assertSame($newName, $updatedName);
    }
}
