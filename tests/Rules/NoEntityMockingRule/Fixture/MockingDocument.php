<?php

namespace Rector\Mockstan\Tests\Rules\NoEntityMockingRule\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Mockstan\Tests\Rules\NoEntityMockingRule\Source\SomeDocument;

final class MockingDocument extends TestCase
{
    public function test(): void
    {
        $someDocumentMock = $this->createMock(SomeDocument::class);
    }
}
