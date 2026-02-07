<?php

namespace Rector\Mockstan\Tests\Rules\NoEntityMockingRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoEntityMockingRule\Source\SimpleObject;

final class SkipMockingOtherObject extends TestCase
{
    public function test(): void
    {
        $someEntityMock = $this->createMock(SimpleObject::class);
    }
}
