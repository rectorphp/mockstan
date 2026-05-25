<?php

namespace Rector\Mockstan\Tests\Rules\NoWithOnStubRule\Fixture;

use PHPUnit\Framework\TestCase;

final class StubWithWith extends TestCase
{
    public function test(): void
    {
        $mock = $this->createMock(\stdClass::class);

        $mock->method('someMethod')
            ->with('arg')
            ->willReturn('value');
    }
}
