<?php

/**
 *     This file is part of the php-svg-optimizer package.
 *     (c) Mathias Reker <github@reker.dk>
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpSvgOptimizer\Services\Providers;

use DOMDocument;
use MathiasReker\PhpSvgOptimizer\Exception\XmlProcessingException;

final class StringProvider extends AbstractProvider
{
    /**
     * Constructor for StringProvider.
     *
     * @param string $inputContent The SVG content as a string
     */
    public function __construct(protected string $inputContent)
    {
        parent::__construct();
    }

    /**
     * Load the input string into a DOMDocument instance.
     *
     * @throws XmlProcessingException If the input content is not valid XML
     */
    #[\Override]
    public function loadContent(): \DOMDocument
    {
        return $this->domDocumentWrapper->loadFromString($this->inputContent);
    }

    /**
     * Get the input SVG content.
     */
    #[\Override]
    public function getInputContent(): string
    {
        return $this->inputContent;
    }
}
