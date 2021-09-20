<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Exceptions;

/**
 * @author Mazen Touati <mazen_touati@hotmail.com>
 */
final class IbanException extends ValidationException
{
    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be a valid IBAN',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be a valid IBAN',
        ],
    ];
}
