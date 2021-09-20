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
 * @author Dick van der Heiden <d.vanderheiden@inthere.nl>
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Michael Weimann <mail@michael-weimann.eu>
 */
final class UuidException extends ValidationException
{
    public const VERSION = 'version';

    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be a valid UUID',
            self::VERSION => '{{name}} must be a valid UUID version {{version}}',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be a valid UUID',
            self::VERSION => '{{name}} must not be a valid UUID version {{version}}',
        ],
    ];

    /**
     * {@inheritDoc}
     */
    protected function chooseTemplate(): string
    {
        if ($this->getParam('version')) {
            return self::VERSION;
        }

        return self::STANDARD;
    }
}
