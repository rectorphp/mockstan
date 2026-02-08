<?php

namespace Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\FirstClass;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\SecondClass;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\SomeInstance;

final class SkipAllMockedButAlwaysNewInTest extends TestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $firstMock;

    private \PHPUnit\Framework\MockObject\MockObject $secondMock;

    protected function setUp(): void
    {
        $this->firstMock = $this->createMock(FirstClass::class);
        $this->secondMock = $this->createMock(SecondClass::class);
    }

    public function testFirst()
    {
        $someInstance = new SomeInstance($this->firstMock, $this->secondMock);
    }

    public function testSecond()
    {
        $someInstance = new SomeInstance($this->firstMock, $this->secondMock);
    }
}
