<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\NoTestMocksRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoTestMocksRule\Source\SomeAllowedType;

final class SkipApiMock extends TestCase
{
    public function test()
    {
        $someAllowedTypeMock = $this->createMock(SomeAllowedType::class);
    }
}
