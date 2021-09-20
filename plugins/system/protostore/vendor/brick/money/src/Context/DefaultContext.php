<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Brick\Money\Context;

use Brick\Money\Context;
use Brick\Money\Currency;

use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;

/**
 * Adjusts a number to the default scale for the currency.
 */
final class DefaultContext implements Context
{
    /**
     * @inheritdoc
     */
    public function applyTo(BigNumber $amount, Currency $currency, int $roundingMode) : BigDecimal
    {
        return $amount->toScale($currency->getDefaultFractionDigits(), $roundingMode);
    }

    /**
     * {@inheritdoc}
     */
    public function getStep() : int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function isFixedScale() : bool
    {
        return true;
    }
}
