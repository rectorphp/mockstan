<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\Int_;
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
 * @see \Rector\Mockstan\Tests\Rules\RequireAtLeastOneRule\RequireAtLeastOneRuleTest
 */
final class RequireAtLeastOneRule implements Rule
{
    public const string ERROR_MESSAGE = 'Using $this->atLeast(0) is meaningless, as it matches any number of calls. Use 1 or higher';

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

        if (! NamingHelper::isName($node->name, 'atLeast')) {
            return [];
        }

        $args = $node->getArgs();
        if ($args === []) {
            return [];
        }

        $firstArgValue = $args[0]->value;
        if (! $firstArgValue instanceof Int_) {
            return [];
        }

        if ($firstArgValue->value >= 1) {
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier(RuleIdentifier::REQUIRE_AT_LEAST_ONE)
            ->build();

        return [$identifierRuleError];
    }
}
