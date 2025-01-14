<?php

/**
 *     This file is part of the php-svg-optimizer package.
 *     (c) Mathias Reker <github@reker.dk>
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpSvgOptimizer\Tests\Unit\Services\Providers;

use MathiasReker\PhpSvgOptimizer\Exception\FileNotFoundException;
use MathiasReker\PhpSvgOptimizer\Exception\IOException;
use MathiasReker\PhpSvgOptimizer\Exception\XmlProcessingException;
use MathiasReker\PhpSvgOptimizer\Services\Data\MetaData;
use MathiasReker\PhpSvgOptimizer\Services\Providers\FileProvider;
use MathiasReker\PhpSvgOptimizer\Services\Util\DomDocumentWrapper;
use MathiasReker\PhpSvgOptimizer\ValueObjects\MetaDataValueObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(FileProvider::class)]
#[CoversClass(MetaData::class)]
#[CoversClass(MetaDataValueObject::class)]
#[CoversClass(DomDocumentWrapper::class)]
final class FileProviderTest extends TestCase
{
    private const string TEST_INPUT_FILE = 'input.svg';

    /**
     * @throws FileNotFoundException
     * @throws IOException
     */
    public function testGetInputContent(): void
    {
        $fileProvider = new FileProvider(self::TEST_INPUT_FILE);
        $content = $fileProvider->getInputContent();

        self::assertStringContainsString('<svg', $content);
        self::assertStringContainsString('</svg>', $content);
    }

    /**
     * @throws XmlProcessingException
     * @throws FileNotFoundException
     * @throws IOException
     */
    public function testOptimize(): void
    {
        $fileProvider = new FileProvider(self::TEST_INPUT_FILE);
        $domDocument = new \DOMDocument();
        $domDocument->loadXML('<svg xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100"/></svg>');

        $fileProvider->optimize($domDocument);

        $outputContent = $fileProvider->getOutputContent();
        self::assertStringContainsString('<svg', $outputContent);
        self::assertStringContainsString('</svg>', $outputContent);
    }

    /**
     * @throws XmlProcessingException
     * @throws FileNotFoundException
     * @throws \InvalidArgumentException
     * @throws IOException
     * @throws \DivisionByZeroError
     */
    public function testGetMetaData(): void
    {
        $fileProvider = new FileProvider(self::TEST_INPUT_FILE);
        $domDocument = new \DOMDocument();
        $domDocument->loadXML('<svg xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100"/></svg>');

        $fileProvider->optimize($domDocument);

        $metaDataValueObject = $fileProvider->getMetaData();

        self::assertSame(filesize(self::TEST_INPUT_FILE), $metaDataValueObject->getOriginalSize());
    }

    /**
     * @throws FileNotFoundException
     * @throws IOException
     */
    public function testGetInputContentThrowsExceptionIfFileDoesNotExist(): void
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('Input file does not exist: nonexistent.svg');

        new FileProvider('nonexistent.svg');
    }

    /**
     * @throws Exception
     * @throws XmlProcessingException
     * @throws FileNotFoundException
     * @throws IOException
     */
    public function testOptimizeThrowsExceptionIfSaveXMLFails(): void
    {
        $fileProvider = new FileProvider(self::TEST_INPUT_FILE);
        $domDocument = $this->createMock(\DOMDocument::class);
        $domDocument->method('saveXML')->willReturn(false);

        $this->expectException(XmlProcessingException::class);
        $this->expectExceptionMessage('Failed to save XML content');

        $fileProvider->optimize($domDocument);
    }

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        file_put_contents(self::TEST_INPUT_FILE, '<svg xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100"/></svg>');
    }

    #[\Override]
    protected function tearDown(): void
    {
        if (file_exists(self::TEST_INPUT_FILE)) {
            unlink(self::TEST_INPUT_FILE);
        }

        parent::tearDown();
    }
}
