<?php

namespace Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Fixture;

use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\FirstClass;
use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\SecondClass;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class SkipConstraintValidatorTest extends ConstraintValidatorTestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $firstMock;

    private \PHPUnit\Framework\MockObject\MockObject $secondMock;

    public function test()
    {
        $this->firstMock = $this->createMock(FirstClass::class);
        $this->secondMock = $this->createMock(SecondClass::class);
    }
}
