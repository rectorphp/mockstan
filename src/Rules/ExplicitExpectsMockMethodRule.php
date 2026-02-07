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
 * @see \Rector\Mockstan\Tests\Rules\ExplicitExpectsMockMethodRule\ExplicitExpectsMockMethodRuleTest
 */
final class ExplicitExpectsMockMethodRule implements Rule
{
    public const string ERROR_MESSAGE = 'PHPUnit mock method is missing explicit expects(), e.g. $this->mock->expects($this->once())->...';

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
        if (! NamingHelper::isName($node->name, 'method')) {
            return [];
        }

        if (! TestClassDetector::isTestClass($scope)) {
            return [];
        }

        if (! $node->var instanceof Variable && ! $node->var instanceof PropertyFetch) {
            return [];
        }

        $callerType = $scope->getType($node->var);
        if (! $callerType->hasMethod('expects')->yes()) {
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier(RuleIdentifier::EXPLICIT_EXPECTS_MOCK_METHOD)
            ->build();

        return [$identifierRuleError];
    }
}
