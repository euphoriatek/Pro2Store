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

interface Quoter
{
    /**
     * Should add quotes to the given string.
     *
     * @param string $string The string to add quotes to
     * @param int $depth The current depth
     *
     * @return string
     */
    public function quote(string $string, int $depth): string;
}
