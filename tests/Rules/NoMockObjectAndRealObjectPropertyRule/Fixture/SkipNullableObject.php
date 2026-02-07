<?php

namespace Rector\Mockstan\Tests\Rules\NoMockObjectAndRealObjectPropertyRule\Fixture;

use PHPUnit\Framework\MockObject\MockObject;

final class SkipNullableObject
{
    private ?MockObject $someObject;
}
