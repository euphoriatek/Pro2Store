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
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class OptionalException extends ValidationException
{
    public const NAMED = 'named';

    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'The value must be optional',
            self::NAMED => '{{name}} must be optional',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'The value must not be optional',
            self::NAMED => '{{name}} must not be optional',
        ],
    ];

    protected function chooseTemplate(): string
    {
        return $this->getParam('name') ? self::NAMED : self::STANDARD;
    }
}
