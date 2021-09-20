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
 * @author João Torquato <joao.otl@gmail.com>
 * @author William Espindola <oi@williamespindola.com.br>
 */
final class CountableException extends ValidationException
{
    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be countable',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be countable',
        ],
    ];
}
