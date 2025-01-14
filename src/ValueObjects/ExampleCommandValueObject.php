<?php

/**
 *     This file is part of the php-svg-optimizer package.
 *     (c) Mathias Reker <github@reker.dk>
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpSvgOptimizer\ValueObjects;

final readonly class ExampleCommandValueObject
{
    /**
     * Constructor for ExampleCommandValueObject.
     *
     * @param string $command The example command
     */
    public function __construct(private string $command)
    {
    }

    /**
     * Get the example command.
     *
     * @return string The example command
     */
    public function getCommand(): string
    {
        return $this->command;
    }
}
