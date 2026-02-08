<?php

namespace Rector\Mockstan\Tests\Rules\AvoidAnyExpectsRule\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeAny extends TestCase
{
    public function test(): void
    {
        $mock = $this->createMock(\stdClass::class);

        $mock->expects($this->any())
            ->method('someMethod')
            ->willReturn('value');
    }
}
