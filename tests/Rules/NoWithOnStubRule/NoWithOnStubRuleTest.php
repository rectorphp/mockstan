<?php

declare(strict_types=1);

namespace Rector\Mockstan\Tests\Rules\NoWithOnStubRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Mockstan\Rules\NoWithOnStubRule;

final class NoWithOnStubRuleTest extends RuleTestCase
{
    /**
     * @param array<int, array<string|int>> $expectedErrorsWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    /**
     * @return Iterator<array<array<int, mixed>, mixed>>
     */
    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/StubWithWith.php', [[NoWithOnStubRule::ERROR_MESSAGE, 13]]];

        yield [__DIR__ . '/Fixture/SkipMockWithExpectsAndWith.php', []];
    }

    protected function getRule(): Rule
    {
        return new NoWithOnStubRule();
    }
}
