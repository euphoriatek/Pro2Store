<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Stringifier;

interface Stringifier
{
    /**
     * Converts the value into string if possible.
     *
     * @param mixed $raw The raw value to be converted.
     * @param int $depth The current depth of the conversion.
     *
     * @return null|string Returns NULL when the conversion is not possible.
     */
    public function stringify($raw, int $depth): ?string;
}
