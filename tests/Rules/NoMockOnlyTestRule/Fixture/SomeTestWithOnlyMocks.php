<?php

namespace Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\FirstClass;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\SecondClass;

final class SomeTestWithOnlyMocks extends TestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $someMock;

    private \PHPUnit\Framework\MockObject\MockObject $anotherMock;

    protected function setUp(): void
    {
        $this->someMock = $this->createMock(FirstClass::class);

        $this->anotherMock = $this->createMock(SecondClass::class);
    }

    public function test()
    {
    }
}
