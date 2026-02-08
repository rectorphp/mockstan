<?php

declare(strict_types=1);

namespace Rector\Mockstan\Testing;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Mockstan\Enum\ClassName;

final class PHPUnitTestAnalyser
{
    public static function isTestClass(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->is(ClassName::TEST_CASE);
    }

    /**
     * @api is used
     */
    public static function isTestClassMethod(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isPublic()) {
            return false;
        }

        if (! $classMethod->isMagic()) {
            return true;
        }

        return str_starts_with($classMethod->name->toString(), 'test');
    }
}
