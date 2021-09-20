<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation;

use Respect\Validation\Exceptions\ValidationException;

/** Interface for validation rules */
/**
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
interface Validatable
{
    /**
     * @param mixed $input
     */
    public function assert($input): void;

    /**
     * @param mixed $input
     */
    public function check($input): void;

    public function getName(): ?string;

    /**
     * @param mixed $input
     * @param mixed[] $extraParameters
     */
    public function reportError($input, array $extraParameters = []): ValidationException;

    public function setName(string $name): Validatable;

    public function setTemplate(string $template): Validatable;

    /**
     * @param mixed $input
     */
    public function validate($input): bool;
}
