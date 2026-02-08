<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\ForbiddenClassToMockRule;

use Iterator;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Mockstan\Rules\ForbiddenClassToMockRule;
use Rector\Mockstan\Tests\Rules\ForbiddenClassToMockRule\Source\SomeForbiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ForbiddenClassToMockRuleTest extends RuleTestCase
{
    /**
     * @param array<int, array<string|int>> $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    /**
     * @return Iterator<array<array<int, mixed>, mixed>>
     */
    public static function provideData(): Iterator
    {
        yield [
            __DIR__ . '/Fixture/SomeMocking.php',
            [
                [sprintf(ForbiddenClassToMockRule::ERROR_MESSAGE, Request::class), 15],
                [sprintf(ForbiddenClassToMockRule::ERROR_MESSAGE, RequestStack::class), 17],
            ],
        ];

        yield [
            __DIR__ . '/Fixture/HandleExtractForbiddenType.php',
            [
                [sprintf(ForbiddenClassToMockRule::ERROR_MESSAGE, SomeForbiddenType::class), 14],
            ],
        ];

        yield [
            __DIR__ . '/Fixture/AllowedType.php',
            [],
        ];
    }

    /**
     * @return string[]
     */
    #[Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/config/configured_rule.neon',
        ];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForbiddenClassToMockRule::class);
    }
}
