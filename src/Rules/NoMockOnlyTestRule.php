<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Mockstan\Analysis\ClassPropertyAnalyser;
use Rector\Mockstan\Enum\ClassName;
use Rector\Mockstan\Enum\RuleIdentifier;
use Rector\Mockstan\Enum\SymfonyClass;
use Rector\Mockstan\Helper\NamingHelper;
use Rector\Mockstan\PHPUnit\TestClassDetector;

/**
 * @see \Rector\Mockstan\Tests\Rules\NoMockOnlyTestRule\NoMockOnlyTestRuleTest
 *
 * @implements Rule<InClassNode>
 */
final readonly class NoMockOnlyTestRule implements Rule
{
    public const string ERROR_MESSAGE = 'Test should have at least one non-mocked property, to test something';

    /**
     * @var string[]
     */
    private const array THIRD_PARTY_TEST_CASES = [
        SymfonyClass::FORM_TYPE_TEST_CASE,
        SymfonyClass::VALIDATOR_TEST_CASE,
    ];

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

        if ($this->isThirdPartyTestCase($classLike)) {
            return [];
        }

        if ($classLike->getProperties() === []) {
            return [];
        }

        if (! ClassPropertyAnalyser::hasExclusivelyMockedProperties($classLike)) {
            return [];
        }

        $testMethods = $this->findTestMethods($classLike);
        if ($testMethods === []) {
            return [];
        }

        if ($this->hasEveryMethodItsNew($testMethods)){
            return [];
        }

        $identifierRuleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
            ->identifier(RuleIdentifier::NO_MOCK_ONLY)
            ->build();

        return [$identifierRuleError];
    }

    private function isThirdPartyTestCase(Class_ $class): bool
    {
        if (! $class->extends instanceof Name) {
            return false;
        }

        return NamingHelper::isNames($class->extends, self::THIRD_PARTY_TEST_CASES);
    }

    private function findTestMethods(Class_ $classLike): array
    {
        $testMethods = [];
        foreach ($classLike->getMethods() as $classMethod) {
            if (!$classMethod->isPublic()) {
                continue;
            }

            $methodName = $classMethod->name->toString();
            if (str_starts_with($methodName, 'test')) {
                continue;
            }

            $testMethods[] = $classMethod;
        }

        return $testMethods;
    }

    private function hasEveryMethodItsNew(array $testMethods): false
    {
        $nodeFinder = new NodeFinder();
        foreach ($testMethods as $testMethod) {
            $new = $nodeFinder->findFirstInstanceOf($testMethod, Node\Expr\New_::class);
            if (!$new instanceof Node\Expr\New_) {
                return false;
            }
        }

        return false;
    }
}
