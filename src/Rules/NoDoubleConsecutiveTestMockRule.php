<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Mockstan\Enum\ClassName;
use Rector\Mockstan\Enum\RuleIdentifier;
use Rector\Mockstan\Enum\TestClassName;
use Rector\Mockstan\Helper\NamingHelper;

/**
 * @implements Rule<MethodCall>
 */
final class NoDoubleConsecutiveTestMockRule implements Rule
{
    public const string ERROR_MESSAGE = 'Do not use "willReturnOnConsecutiveCalls()" and "willReturnCallback()" on the same mock. Use "willReturnCallback() only instead to make test more clear.';

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return RuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // 1. detect if we're in a PHPUnit test case
        if (! $scope->isInClass()) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection->is(ClassName::TEST_CASE)) {
            return [];
        }

        // 2. find a phpunit mock call, that uses "willReturnOnConsecutiveCalls" and "willReturnCallback" on the same line
        if (! $node->var instanceof MethodCall) {
            return [];
        }

        $parentCall = $node->var;
        if (! $this->containsBothMethodCallNames($node, $parentCall)) {
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier(RuleIdentifier::NO_DOUBLE_CONSECUTIVE_TEST_MOCK)
            ->build();

        return [$identifierRuleError];
    }

    private function containsBothMethodCallNames(MethodCall $firstMethodCall, MethodCall $secondMethodCall): bool
    {
        if (NamingHelper::isName($firstMethodCall->name, 'willReturnOnConsecutiveCalls') && NamingHelper::isName($secondMethodCall->name, 'willReturnCallback')) {
            return true;
        }

        return NamingHelper::isName($secondMethodCall->name, 'willReturnOnConsecutiveCalls') && NamingHelper::isName($firstMethodCall->name, 'willReturnCallback');
    }
}
