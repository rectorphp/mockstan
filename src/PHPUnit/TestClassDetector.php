<?php

declare(strict_types=1);

namespace Rector\Mockstan\PHPUnit;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Mockstan\Enum\ClassName;

final class TestClassDetector
{
    /**
     * @var string[]
     */
    private const array TEST_FILE_SUFFIXES = [
        'Test.php',
        'TestCase.php',
        'Context.php',
    ];

    public static function isTestClass(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        if ($classReflection->is(ClassName::TEST_CASE)) {
            return true;
        }

        foreach (self::TEST_FILE_SUFFIXES as $testFileSuffix) {
            if (str_ends_with($scope->getFile(), $testFileSuffix)) {
                return true;
            }
        }

        return false;
    }
}
