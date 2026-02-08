<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Mockstan\Enum\ClassName;
use Rector\Mockstan\Enum\RuleIdentifier;
use Rector\Mockstan\Enum\SymfonyClass;
use Rector\Mockstan\PHPUnit\TestClassDetector;

/**
 * @see \Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\NoMockOnlyTestRuleTest
 *
 * @implements Rule<InClassNode>
 */
final readonly class NoMockOnlyTestRule implements Rule
{
    public const string ERROR_MESSAGE = 'Test should have at least one non-mocked property, to test something';

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! TestClassDetector::isTestClass($scope)) {
            return [];
        }

        $classLike = $node->getOriginalNode();
        if (! $classLike instanceof Class_) {
            return [];
        }

        if ($classLike->extends instanceof Name && $classLike->extends->toString() === SymfonyClass::VALIDATOR_TEST_CASE) {
            return [];
        }

        if ($classLike->getProperties() === []) {
            return [];
        }

        $hasExclusivelyMockedProperties = true;
        $hasSomeProperties = false;

        foreach ($classLike->getProperties() as $property) {
            if (! $property->type instanceof Name) {
                continue;
            }

            $propertyClassName = $property->type->toString();

            if ($propertyClassName !== ClassName::MOCK_OBJECT) {
                $hasExclusivelyMockedProperties = false;
            } else {
                $hasSomeProperties = true;
            }
        }

        if ($hasExclusivelyMockedProperties === false || $hasSomeProperties === false) {
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier(RuleIdentifier::NO_MOCK_ONLY)
            ->build();

        return [$identifierRuleError];
    }
}
