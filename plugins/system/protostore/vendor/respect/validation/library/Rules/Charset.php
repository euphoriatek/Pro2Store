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

use Respect\Validation\Exceptions\ComponentException;

use function array_diff;
use function in_array;
use function mb_detect_encoding;
use function mb_list_encodings;

/**
 * Validates if a string is in a specific charset.
 *
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author William Espindola <oi@williamespindola.com.br>
 */
final class Charset extends AbstractRule
{
    /**
     * @var string[]
     */
    private $charset;

    /**
     * Initializes the rule.
     *
     * @throws ComponentException
     */
    public function __construct(string ...$charset)
    {
        $available = mb_list_encodings();
        if (!empty(array_diff($charset, $available))) {
            throw new ComponentException('Invalid charset');
        }

        $this->charset = $charset;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        $detectedEncoding = mb_detect_encoding($input, $this->charset, true);

        return in_array($detectedEncoding, $this->charset, true);
    }
}
