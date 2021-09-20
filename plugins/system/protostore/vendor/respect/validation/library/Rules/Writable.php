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

use Psr\Http\Message\StreamInterface;
use SplFileInfo;

use function is_string;
use function is_writable;

/**
 * Validates if the given input is writable file.
 *
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class Writable extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if ($input instanceof SplFileInfo) {
            return $input->isWritable();
        }

        if ($input instanceof StreamInterface) {
            return $input->isWritable();
        }

        return is_string($input) && is_writable($input);
    }
}
