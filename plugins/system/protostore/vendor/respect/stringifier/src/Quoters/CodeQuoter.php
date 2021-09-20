<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Stringifier\Quoters;

use Respect\Stringifier\Quoter;

/**
 * Add "`" quotes around a string depending on its level.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class CodeQuoter implements Quoter
{
    /**
     * {@inheritdoc}
     */
    public function quote(string $string, int $depth): string
    {
        if (0 === $depth) {
            return sprintf('`%s`', $string);
        }

        return $string;
    }
}
