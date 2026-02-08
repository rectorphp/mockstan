<?php

declare(strict_types=1);

namespace Rector\Mockstan\ValueObject;

final readonly class PropertyAnalysis
{
    public function __construct(
        private bool $hasExclusivelyMockedProperties,
    ) {
    }

    public function hasExclusivelyMockedProperties(): bool
    {
        return $this->hasExclusivelyMockedProperties;
    }
}
