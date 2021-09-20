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

use function is_bool;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

/**
 * Converts a boolean value into a string.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class BoolStringifier implements Stringifier
{
    /**
     * @var Quoter
     */
    private $quoter;

    /**
     * Initializes the stringifier.
     *
     * @param Quoter $quoter
     */
    public function __construct(Quoter $quoter)
    {
        $this->quoter = $quoter;
    }

    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        if (!is_bool($raw)) {
            return null;
        }

        return $this->quoter->quote($raw ? 'TRUE' : 'FALSE', $depth);
    }
}
