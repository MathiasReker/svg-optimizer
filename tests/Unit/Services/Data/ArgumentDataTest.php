<?php

/**
 *     This file is part of the php-svg-optimizer package.
 *     (c) Mathias Reker <github@reker.dk>
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpSvgOptimizer\Tests\Unit\Services\Data;

use MathiasReker\PhpSvgOptimizer\Enums\Command;
use MathiasReker\PhpSvgOptimizer\Enums\Option;
use MathiasReker\PhpSvgOptimizer\Services\Data\ArgumentData;
use MathiasReker\PhpSvgOptimizer\ValueObjects\ArgumentOptionValueObject;
use MathiasReker\PhpSvgOptimizer\ValueObjects\CommandOptionValueObject;
use MathiasReker\PhpSvgOptimizer\ValueObjects\ExampleCommandValueObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ArgumentData::class)]
#[CoversClass(CommandOptionValueObject::class)]
#[CoversClass(ArgumentOptionValueObject::class)]
#[CoversClass(ExampleCommandValueObject::class)]
#[CoversClass(Command::class)]
#[CoversClass(Option::class)]
final class ArgumentDataTest extends TestCase
{
    private const string EXAMPLE_COMMAND = 'vendor/bin/svg-optimizer --dry-run process /path/to/svgs';

    private ArgumentData $argumentData;

    public function testGetOptions(): void
    {
        $options = $this->argumentData->getOptions();

        self::assertArrayHasKey(Option::HELP->value, $options);
        $helpOption = $options[Option::HELP->value];

        self::assertSame(Option::HELP->getShorthand(), $helpOption->getShorthand());
        self::assertSame(Option::HELP->getFull(), $helpOption->getFull());
        self::assertSame(Option::HELP->getDescription(), $helpOption->getDescription());
    }

    public function testGetCommands(): void
    {
        $commands = $this->argumentData->getCommands();

        self::assertArrayHasKey(Command::PROCESS->value, $commands);
        $processCommand = $commands[Command::PROCESS->value];

        self::assertSame(Command::PROCESS->getTitle(), $processCommand->getTitle());
        self::assertSame(Command::PROCESS->getDescription(), $processCommand->getDescription());
    }

    public function testGetOption(): void
    {
        $option = $this->argumentData->getOption(Option::HELP->value);

        self::assertInstanceOf(ArgumentOptionValueObject::class, $option);
        self::assertSame(Option::HELP->getShorthand(), $option->getShorthand());
        self::assertSame(Option::HELP->getFull(), $option->getFull());
        self::assertSame(Option::HELP->getDescription(), $option->getDescription());
    }

    public function testGetExamples(): void
    {
        $examples = $this->argumentData->getExamples();

        self::assertCount(3, $examples);
        $example = $examples[0];

        self::assertSame(self::EXAMPLE_COMMAND, $example->getCommand());
    }

    public function testGetFormat(): void
    {
        $format = $this->argumentData->getFormat();

        self::assertSame('vendor/bin/svg-optimizer [options] process <path1> <path2> ...', $format);
    }

    #[\Override]
    protected function setUp(): void
    {
        $this->argumentData = new ArgumentData();
    }
}
