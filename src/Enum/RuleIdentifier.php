<?php

declare(strict_types=1);

namespace Rector\Mockstan\Enum;

/**
 * @api
 */
final class RuleIdentifier
{
    public const string NO_MOCK_ONLY = 'mockstan.noMockOnly';

    public const string NO_MOCK_OBJECT_AND_REAL_OBJECT_PROPERTY = 'mockstan.noMockObjectAndRealObjectProperty';

    public const string NO_DOUBLE_CONSECUTIVE_TEST_MOCK = 'mockstan.noDoubleConsecutiveTestMock';

    public const string NO_ENTITY_MOCKING = 'mockstan.noEntityMocking';

    public const string NO_DOCUMENT_MOCKING = 'mockstan.noDocumentMocking';

    public const string EXPLICIT_EXPECTS_MOCK_METHOD = 'mockstan.explicitExpectsMockMethod';

    public const string FORBIDDEN_CLASS_TO_MOCK = 'mockstan.forbiddenClassToMock';

    public const string AVOID_ANY_EXPECTS = 'mockstan.avoidAnyExpects';
}
