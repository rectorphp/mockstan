<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source;

final class SomeInstance
{
    public function __construct(...$dependencies)
    {
    }
}
