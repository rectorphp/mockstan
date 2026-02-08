<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\ForbiddenClassToMockRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\ForbiddenClassToMockRule\Source\SomeForbiddenType;

final class AllowedType extends TestCase
{
    public function test()
    {
        $mock = $this->createMock(AllowedType::class);
    }
}
