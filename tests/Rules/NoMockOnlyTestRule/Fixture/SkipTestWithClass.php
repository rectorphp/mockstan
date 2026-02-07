<?php

namespace Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\FirstClass;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\SecondClass;

final class SkipTestWithClass extends TestCase
{
    private FirstClass $firstClass;

    private \PHPUnit\Framework\MockObject\MockObject $anotherMock;

    protected function setUp(): void
    {
        $this->firstClass = new FirstClass();

        $this->anotherMock = $this->createMock(SecondClass::class);
    }
}
