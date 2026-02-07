<?php

namespace Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipSoleProperty extends TestCase
{
    private array $someItems = [];
}
