<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Helpers\CanCompareValues;

/**
 * Validates whether the input is between two other values.
 *
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class Between extends AbstractEnvelope
{
    use CanCompareValues;

    /**
     * Initializes the rule.
     *
     * @param mixed $minValue
     * @param mixed $maxValue
     *
     * @throws ComponentException
     */
    public function __construct($minValue, $maxValue)
    {
        if ($this->toComparable($minValue) >= $this->toComparable($maxValue)) {
            throw new ComponentException('Minimum cannot be less than or equals to maximum');
        }

        parent::__construct(
            new AllOf(
                new Min($minValue),
                new Max($maxValue)
            ),
            [
                'minValue' => $minValue,
                'maxValue' => $maxValue,
            ]
        );
    }
}
