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
 * Exception class for FloatType rule.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Reginaldo Junior <76regi@gmail.com>
 */
final class FloatTypeException extends ValidationException
{
    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be of type float',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be of type float',
        ],
    ];
}
