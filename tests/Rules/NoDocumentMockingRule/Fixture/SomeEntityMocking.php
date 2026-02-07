<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\NoDocumentMockingRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoDocumentMockingRule\Source\Entity\SomeEntity;

final class SomeEntityMocking extends TestCase
{
    public function test()
    {
        $someMock = $this->createMock(SomeEntity::class);
    }
}
