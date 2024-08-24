<?php
/**
 * This file is part of the php-svg-optimizer package.
 * (c) Mathias Reker <github@reker.dk>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpSvgOptimizer\Tests\Unit\Services\Providers;

use MathiasReker\PhpSvgOptimizer\Services\MetaData;
use MathiasReker\PhpSvgOptimizer\Services\Providers\StringProvider;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringProvider::class)]
#[CoversClass(MetaData::class)]
final class StringProviderTest extends TestCase
{
    private const TEST_INPUT_STRING = '<svg xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100"/></svg>';

    public function testLoad(): void
    {
        $stringProvider = new StringProvider(self::TEST_INPUT_STRING);
        $domDocument = $stringProvider->load();

        Assert::assertInstanceOf(\DOMDocument::class, $domDocument);
    }

    public function testOptimize(): void
    {
        $stringProvider = new StringProvider(self::TEST_INPUT_STRING);
        $domDocument = $stringProvider->load();

        $stringProvider->optimize($domDocument);

        Assert::assertSame('<svg xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100"/></svg>', $stringProvider->getOutputContent());
    }

    public function testGetInputContent(): void
    {
        $stringProvider = new StringProvider(self::TEST_INPUT_STRING);

        Assert::assertSame(self::TEST_INPUT_STRING, $stringProvider->getInputContent());
    }

    public function testGetMetaData(): void
    {
        $stringProvider = new StringProvider(self::TEST_INPUT_STRING);
        $domDocument = $stringProvider->load();

        $stringProvider->optimize($domDocument);

        $metaData = $stringProvider->getMetaData();

        Assert::assertArrayHasKey('originalSize', $metaData);
        Assert::assertArrayHasKey('optimizedSize', $metaData);
        Assert::assertArrayHasKey('savedBytes', $metaData);
        Assert::assertArrayHasKey('savedPercentage', $metaData);

        $originalSize = mb_strlen(self::TEST_INPUT_STRING);
        $optimizedSize = mb_strlen($stringProvider->getOutputContent());

        Assert::assertSame($originalSize, $metaData['originalSize']);
        Assert::assertSame($optimizedSize, $metaData['optimizedSize']);
    }

    public function testOptimizeThrowsExceptionIfSaveXMLFails(): void
    {
        $stringProvider = new StringProvider(self::TEST_INPUT_STRING);
        $domDocument = $this->createMock(\DOMDocument::class);
        $domDocument->method('saveXML')->willReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to save XML content.');

        $stringProvider->optimize($domDocument);
    }
}
