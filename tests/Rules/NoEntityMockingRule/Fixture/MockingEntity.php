<?php

namespace Rector\Mockstan\Tests\Rules\NoEntityMockingRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoEntityMockingRule\Source\SomeEntity;

final class MockingEntity extends TestCase
{
    public function test(): void
    {
        $someEntityMock = $this->createMock(SomeEntity::class);
    }
}
