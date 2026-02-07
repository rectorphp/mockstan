<?php

namespace Rector\Mockstan\Tests\Rules\NoMockObjectAndRealObjectPropertyRule\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use Rector\Mockstan\Tests\Rules\NoMockObjectAndRealObjectPropertyRule\Source\SomeObject;

final class SkipOneOrTheOther
{
    private SomeObject $someObject;

    private MockObject $anotherMock;
}
