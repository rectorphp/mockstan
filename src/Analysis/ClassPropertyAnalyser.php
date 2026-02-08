<?php

declare(strict_types=1);

namespace Rector\Mockstan\Analysis;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Mockstan\Enum\ClassName;

final class ClassPropertyAnalyser
{
    public static function hasExclusivelyMockedProperties(Class_ $class): bool
    {
        if ($class->getProperties() === []) {
            return false;
        }

        foreach ($class->getProperties() as $property) {
            if (! self::isMockObjectProperty($property)) {
                return false;
            }
        }


        return true;
    }

    public static function isMockObjectProperty(Property $property): bool
    {
        if (! $property->type instanceof Name) {
            return false;
        }

        $propertyClassName = $property->type->toString();
        return $propertyClassName === ClassName::MOCK_OBJECT;
    }
}
