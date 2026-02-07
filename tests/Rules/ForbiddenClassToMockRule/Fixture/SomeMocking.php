<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\ForbiddenClassToMockRule\Fixture;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class SomeMocking extends TestCase
{
    public function test()
    {
        $request = $this->createMock(Request::class);

        $requestStack = $this->createMock(RequestStack::class);
    }
}
