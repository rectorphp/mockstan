<?php

namespace Rector\Mockstan\Tests\Rules\NoWithOnStubRule\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipMockWithExpectsAndWith extends TestCase
{
    public function test(): void
    {
        $mock = $this->createMock(\stdClass::class);

        $mock->expects($this->once())
            ->method('someMethod')
            ->with('arg')
            ->willReturn('value');
    }
}
