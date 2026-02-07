<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Mockstan\Doctrine\DoctrineEntityDocumentAnalyser;
use Rector\Mockstan\Enum\RuleIdentifier;
use Rector\Mockstan\NodeAnalyzer\MethodCallNameAnalyzer;

/**
 * The ORM entities and ODM documents should never be mocked, as it leads to typeless code.
 * Use them directly instead.
 *
 * @see \Rector\Mockstan\Tests\Rules\NoEntityMockingRule\NoEntityMockingRuleTest
 *
 * @implements Rule<MethodCall>
 */
final readonly class NoEntityMockingRule implements Rule
{
    public const string ERROR_MESSAGE = 'Instead of entity or document mocking, create object directly to get better type support';

    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! MethodCallNameAnalyzer::isThisMethodCall($node, 'createMock')) {
            return [];
        }

        $firstArg = $node->getArgs()[0];
        $mockedClassType = $scope->getType($firstArg->value);

        foreach ($mockedClassType->getConstantStrings() as $constantStringType) {
            if (! $this->reflectionProvider->hasClass($constantStringType->getValue())) {
                continue;
            }

            $classReflection = $this->reflectionProvider->getClass($constantStringType->getValue());
            if (! DoctrineEntityDocumentAnalyser::isEntityClass($classReflection)) {
                continue;
            }

            $ruleError = RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier(RuleIdentifier::NO_ENTITY_MOCKING)
                ->build();

            return [$ruleError];
        }

        return [];
    }
}
