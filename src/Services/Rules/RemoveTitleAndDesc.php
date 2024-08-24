<?php
/**
 * This file is part of the php-svg-optimizer package.
 * (c) Mathias Reker <github@reker.dk>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpSvgOptimizer\Services\Rules;

class RemoveTitleAndDesc implements SvgOptimizerRuleInterface
{
    /**
     * Remove the title and desc elements from the SVG document.
     *
     * @param \DOMDocument $domDocument the DOMDocument instance representing the SVG file to be optimized
     */
    public function optimize(\DOMDocument $domDocument): void
    {
        $this->removeElementsByTagName($domDocument, 'title');
        $this->removeElementsByTagName($domDocument, 'desc');
    }

    /**
     * Remove all elements with the given tag name from the DOMDocument.
     *
     * @param \DOMDocument $domDocument the DOMDocument instance representing the SVG file to be optimized
     * @param string       $tagName     the tag name of the elements to be removed
     */
    private function removeElementsByTagName(\DOMDocument $domDocument, string $tagName): void
    {
        $domNodeList = $domDocument->getElementsByTagName($tagName);

        while ($domNodeList->length > 0) {
            $element = $domNodeList->item(0);

            if ($element instanceof \DOMNode && $element->parentNode instanceof \DOMNode) {
                $element->parentNode->removeChild($element);
            }
        }
    }
}
