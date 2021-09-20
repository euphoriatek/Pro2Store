<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use function is_object;
use function method_exists;
use Respect\Stringifier\Stringifier;

/**
 * Converts a object that implements the __toString() magic method into a string.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class StringableObjectStringifier implements Stringifier
{
    /**
     * @var Stringifier
     */
    private $stringifier;

    /**
     * Initializes the stringifier.
     *
     * @param Stringifier $stringifier
     */
    public function __construct(Stringifier $stringifier)
    {
        $this->stringifier = $stringifier;
    }

    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        if (!is_object($raw)) {
            return null;
        }

        if (!method_exists($raw, '__toString')) {
            return null;
        }

        return $this->stringifier->stringify($raw->__toString(), $depth);
    }
}
