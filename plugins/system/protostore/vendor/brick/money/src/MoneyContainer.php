<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Brick\Money;

use Brick\Math\BigNumber;

/**
 * Common interface for Money, RationalMoney and MoneyBag.
 */
interface MoneyContainer
{
    /**
     * Returns the amounts contained in this money container, indexed by currency code.
     *
     * @return BigNumber[]
     */
    public function getAmounts() : array;
}
