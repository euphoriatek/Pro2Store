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

use function get_class;
use function get_object_vars;
use function is_object;
use function sprintf;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

/**
 * Converts an object into a string.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class ObjectStringifier implements Stringifier
{
    /**
     * @var Stringifier
     */
    private $stringifier;

    /**
     * @var Quoter
     */
    private $quoter;

    /**
     * Initializes the stringifier.
     *
     * @param Stringifier $stringifier
     * @param Quoter $quoter
     */
    public function __construct(Stringifier $stringifier, Quoter $quoter)
    {
        $this->stringifier = $stringifier;
        $this->quoter = $quoter;
    }

    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        if (!is_object($raw)) {
            return null;
        }

        return $this->quoter->quote(
            sprintf(
                '[object] (%s: %s)',
                get_class($raw),
                $this->stringifier->stringify(get_object_vars($raw), $depth + 1)
            ),
            $depth
        );
    }
}
