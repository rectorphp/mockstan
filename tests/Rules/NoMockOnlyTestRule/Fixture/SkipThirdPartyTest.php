<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Fixture;

use Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\Source\FirstClass;
use Symfony\Component\Form\Test\TypeTestCase;

final class SkipThirdPartyTest extends TypeTestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $firstMock;

    protected function setUp(): void
    {
        $this->firstMock = $this->createMock(FirstClass::class);
    }
}
