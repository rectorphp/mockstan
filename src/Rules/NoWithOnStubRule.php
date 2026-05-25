<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Mockstan\Enum\RuleIdentifier;
use Rector\Mockstan\Helper\NamingHelper;
use Rector\Mockstan\PHPUnit\TestClassDetector;

/**
 * @implements Rule<MethodCall>
 *
 * @see \Rector\Mockstan\Tests\Rules\NoWithOnStubRule\NoWithOnStubRuleTest
 */
final class NoWithOnStubRule implements Rule
{
    public const string ERROR_MESSAGE = 'Using with() on a stub is misleading and deprecated by PHPUnit. Use explicit expects() to turn it into a mock, or drop with()';

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return IdentifierRuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! NamingHelper::isName($node->name, 'with')) {
            return [];
        }

        if (! TestClassDetector::isTestClass($scope)) {
            return [];
        }

        if (! $node->var instanceof MethodCall) {
            return [];
        }

        $methodCall = $node->var;
        if (! NamingHelper::isName($methodCall->name, 'method')) {
            return [];
        }

        if ($methodCall->var instanceof MethodCall && NamingHelper::isName($methodCall->var->name, 'expects')) {
            return [];
        }

        if (! $methodCall->var instanceof Variable && ! $methodCall->var instanceof PropertyFetch) {
            return [];
        }

        $callerType = $scope->getType($methodCall->var);
        if (! $callerType->hasMethod('expects')->yes()) {
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier(RuleIdentifier::NO_WITH_ON_STUB)
            ->build();

        return [$identifierRuleError];
    }
}
