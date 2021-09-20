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
 * @author Andre Ramaciotti <andre@ramaciotti.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class XdigitException extends FilteredValidationException
{
    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} contain only hexadecimal digits',
            self::EXTRA => '{{name}} contain only hexadecimal digits and {{additionalChars}}',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not contain hexadecimal digits',
            self::EXTRA => '{{name}} must not contain hexadecimal digits or {{additionalChars}}',
        ],
    ];
}
