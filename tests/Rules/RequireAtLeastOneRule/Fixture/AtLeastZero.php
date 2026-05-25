<?php

namespace Rector\Mockstan\Tests\Rules\RequireAtLeastOneRule\Fixture;

use PHPUnit\Framework\TestCase;

final class AtLeastZero extends TestCase
{
    public function test(): void
    {
        $mock = $this->createMock(\stdClass::class);

        $mock->expects($this->atLeast(0))
            ->method('someMethod')
            ->willReturn('value');
    }
}
