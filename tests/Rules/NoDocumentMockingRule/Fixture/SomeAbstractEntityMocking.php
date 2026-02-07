<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\NoDocumentMockingRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoDocumentMockingRule\Source\Entity\AbstractSomeEntity;

final class SomeAbstractEntityMocking extends TestCase
{
    public function test()
    {
        $someMock = $this->createMock(AbstractSomeEntity::class);
    }
}
