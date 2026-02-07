<?php

declare(strict_types=1);

namespace Rector\Mockstan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use Rector\Mockstan\Enum\RuleIdentifier;
use Rector\Mockstan\Enum\SymfonyClass;
use Rector\Mockstan\Helper\NamingHelper;

/**
 * @implements Rule<MethodCall>
 */
final readonly class ForbiddenClassToMockRule implements Rule
{
    /**
     * @api
     */
    public const string ERROR_MESSAGE = 'Mocking "%s" class is forbidden. Use direct new instance or anonymous class instead for better static analysis';

    /**
     * @var string[]
     */
    private const array MOCKING_METHOD_NAMES = ['createMock', 'createPartialMock', 'createConfiguredMock', 'createStub'];

    /**
     * @var string[]
     */
    private const array FORBIDDEN_TYPES = [
        SymfonyClass::REQUEST,
    ];

    /**
     * @param string[] $forbiddenTypes
     */
    public function __construct(
        private array $forbiddenTypes = []
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
        if (! NamingHelper::isNames($node->name, self::MOCKING_METHOD_NAMES)) {
            return [];
        }

        $mockedObjectType = $this->resolveMockedObjectType($node, $scope);
        if (! $mockedObjectType instanceof ObjectType) {
            return [];
        }

        if (! $this->isForbiddenType($mockedObjectType)) {
            return [];
        }

        $errorMessage = sprintf(self::ERROR_MESSAGE, $mockedObjectType->getClassName());

        $ruleError = RuleErrorBuilder::message($errorMessage)
            ->identifier(RuleIdentifier::NO_TEST_MOCKS)
            ->build();

        return [$ruleError];
    }

    private function resolveMockedObjectType(MethodCall $methodCall, Scope $scope): ?ObjectType
    {
        $args = $methodCall->getArgs();

        $mockedArgValue = $args[0]->value;
        $variableType = $scope->getType($mockedArgValue);

        foreach ($variableType->getConstantStrings() as $constantStringType) {
            return new ObjectType($constantStringType->getValue());
        }

        return null;
    }

    private function isForbiddenType(ObjectType $objectType): bool
    {
        $groupedForbiddenTypes = array_merge(self::FORBIDDEN_TYPES, $this->forbiddenTypes);

        foreach ($groupedForbiddenTypes as $forbiddenType) {
            if ($objectType->getClassName() === $forbiddenType) {
                return true;
            }

            if ($objectType->isInstanceOf($forbiddenType)->yes()) {
                return true;
            }
        }

        return false;
    }
}
