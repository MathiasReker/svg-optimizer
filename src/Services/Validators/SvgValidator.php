<?php
/**
 * This file is part of the php-svg-optimizer package.
 * (c) Mathias Reker <github@reker.dk>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpSvgOptimizer\Services\Validators;

class SvgValidator
{
    /**
     * Checks if the provided content is a valid SVG.
     *
     * @param string $svgContent the SVG content to be validated
     *
     * @return bool true if the content is valid SVG, false otherwise
     */
    public function isValid(string $svgContent): bool
    {
        // Remove any XML declaration
        $cleanedContent = preg_replace('/^\s*<\?xml[^>]*\?>\s*/i', '', $svgContent);

        // Check if the cleaned content starts with a valid <svg> tag
        $match = preg_match('/^\s*<svg\b[^>]*>/i', (string) $cleanedContent);

        return 1 === $match;
    }
}
