<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
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
 * @see \Rector\Mockstan\Tests\Rules\AvoidAnyExpectsRule\AvoidAnyExpectsRuleTest
 */
final class AvoidAnyExpectsRule implements Rule
{
    public const string ERROR_MESSAGE = 'Using $this->any() on mock is ambigous. Use explicit count or change to a stub';

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
        if (! TestClassDetector::isTestClass($scope)) {
            return [];
        }

        if (! NamingHelper::isName($node->name, 'expects')) {
            return [];
        }

        $firstArg = $node->getArgs()[0];
        if (! $firstArg->value instanceof MethodCall) {
            return [];
        }

        $nestedCall = $firstArg->value;
        if (! NamingHelper::isName($nestedCall->name, 'any')) {
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier(RuleIdentifier::AVOID_ANY_EXPECTS)
            ->build();

        return [$identifierRuleError];
    }
}
