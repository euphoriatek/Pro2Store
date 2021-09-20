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

use function implode;
use function is_string;
use function preg_match;

/**
 * Validates if the input does not contain an emoji.
 *
 * @author Mazen Touati <mazen_touati@hotmail.com>
 */
final class NotEmoji extends AbstractRule
{
    private const RANGES = [
        '\x{0023}\x{FE0F}\x{20E3}',
        '\x{0023}\x{20E3}',
        '\x{002A}\x{FE0F}\x{20E3}',
        '\x{002A}\x{20E3}',
        '\x{0030}\x{FE0F}\x{20E3}',
        '\x{0030}\x{20E3}',
        '\x{0031}\x{FE0F}\x{20E3}',
        '\x{0031}\x{20E3}',
        '\x{0032}\x{FE0F}\x{20E3}',
        '\x{0032}\x{20E3}',
        '\x{0033}\x{FE0F}\x{20E3}',
        '\x{0033}\x{20E3}',
        '\x{0034}\x{FE0F}\x{20E3}',
        '\x{0034}\x{20E3}',
        '\x{0035}\x{FE0F}\x{20E3}',
        '\x{0035}\x{20E3}',
        '\x{0036}\x{FE0F}\x{20E3}',
        '\x{0036}\x{20E3}',
        '\x{0037}\x{FE0F}\x{20E3}',
        '\x{0037}\x{20E3}',
        '\x{0038}\x{FE0F}\x{20E3}',
        '\x{0038}\x{20E3}',
        '\x{0039}\x{FE0F}\x{20E3}',
        '\x{0039}\x{20E3}',
        '\x{1F004}',
        '\x{1F0CF}',
        '[\x{1F170}-\x{1F171}]',
        '[\x{1F17E}-\x{1F17F}]',
        '\x{1F18E}',
        '[\x{1F191}-\x{1F19A}]',
        '[\x{1F1E6}-\x{1F1FF}]',
        '[\x{1F201}-\x{1F202}]',
        '\x{1F21A}',
        '\x{1F22F}',
        '[\x{1F232}-\x{1F23A}]',
        '[\x{1F250}-\x{1F251}]',
        '[\x{1F300}-\x{1F321}]',
        '[\x{1F324}-\x{1F393}]',
        '[\x{1F396}-\x{1F397}]',
        '[\x{1F399}-\x{1F39B}]',
        '[\x{1F39E}-\x{1F3F0}]',
        '[\x{1F3F3}-\x{1F3F5}]',
        '[\x{1F3F7}-\x{1F4FD}]',
        '[\x{1F4FF}-\x{1F53D}]',
        '[\x{1F549}-\x{1F54E}]',
        '[\x{1F550}-\x{1F567}]',
        '[\x{1F56F}-\x{1F570}]',
        '[\x{1F573}-\x{1F57A}]',
        '\x{1F587}',
        '[\x{1F58A}-\x{1F58D}]',
        '\x{1F590}',
        '[\x{1F595}-\x{1F596}]',
        '[\x{1F5A4}-\x{1F5A5}]',
        '\x{1F5A8}',
        '[\x{1F5B1}-\x{1F5B2}]',
        '\x{1F5BC}',
        '[\x{1F5C2}-\x{1F5C4}]',
        '[\x{1F5D1}-\x{1F5D3}]',
        '[\x{1F5DC}-\x{1F5DE}]',
        '\x{1F5E1}',
        '\x{1F5E3}',
        '\x{1F5E8}',
        '\x{1F5EF}',
        '\x{1F5F3}',
        '[\x{1F5FA}-\x{1F64F}]',
        '[\x{1F680}-\x{1F6C5}]',
        '[\x{1F6CB}-\x{1F6D2}]',
        '[\x{1F6E0}-\x{1F6E5}]',
        '\x{1F6E9}',
        '[\x{1F6EB}-\x{1F6EC}]',
        '\x{1F6F0}',
        '[\x{1F6F3}-\x{1F6F9}]',
        '[\x{1F910}-\x{1F93A}]',
        '[\x{1F93C}-\x{1F93E}]',
        '[\x{1F940}-\x{1F945}]',
        '[\x{1F947}-\x{1F970}]',
        '[\x{1F973}-\x{1F976}]',
        '\x{1F97A}',
        '[\x{1F97C}-\x{1F9A2}]',
        '[\x{1F9B0}-\x{1F9B9}]',
        '[\x{1F9C0}-\x{1F9C2}]',
        '[\x{1F9D0}-\x{1F9FF}]',
        '\x{00A9}',
        '\x{00AE}',
        '\x{203C}',
        '\x{2049}',
        '\x{2122}',
        '\x{2139}',
        '[\x{2194}-\x{2199}]',
        '[\x{21A9}-\x{21AA}]',
        '[\x{231A}-\x{231B}]',
        '\x{2328}',
        '\x{23CF}',
        '[\x{23E9}-\x{23F3}]',
        '[\x{23F8}-\x{23FA}]',
        '\x{24C2}',
        '[\x{25AA}-\x{25AB}]',
        '\x{25B6}',
        '\x{25C0}',
        '[\x{25FB}-\x{25FE}]',
        '[\x{2600}-\x{2604}]',
        '\x{260E}',
        '\x{2611}',
        '[\x{2614}-\x{2615}]',
        '\x{2618}',
        '\x{261D}',
        '\x{2620}',
        '[\x{2622}-\x{2623}]',
        '\x{2626}',
        '\x{262A}',
        '[\x{262E}-\x{262F}]',
        '[\x{2638}-\x{263A}]',
        '\x{2640}',
        '\x{2642}',
        '[\x{2648}-\x{2653}]',
        '[\x{265F}-\x{2660}]',
        '\x{2663}',
        '[\x{2665}-\x{2666}]',
        '\x{2668}',
        '\x{267B}',
        '[\x{267E}-\x{267F}]',
        '[\x{2692}-\x{2697}]',
        '\x{2699}',
        '[\x{269B}-\x{269C}]',
        '[\x{26A0}-\x{26A1}]',
        '[\x{26AA}-\x{26AB}]',
        '[\x{26B0}-\x{26B1}]',
        '[\x{26BD}-\x{26BE}]',
        '[\x{26C4}-\x{26C5}]',
        '\x{26C8}',
        '[\x{26CE}-\x{26CF}]',
        '\x{26D1}',
        '[\x{26D3}-\x{26D4}]',
        '\x{26EA}',
        '[\x{26F0}-\x{26F5}]',
        '[\x{26F7}-\x{26FA}]',
        '\x{26FD}',
        '\x{2702}',
        '\x{2705}',
        '[\x{2708}-\x{270D}]',
        '\x{270F}',
        '\x{2712}',
        '\x{2714}',
        '\x{2716}',
        '\x{271D}',
        '\x{2721}',
        '\x{2728}',
        '[\x{2733}-\x{2734}]',
        '\x{2744}',
        '\x{2747}',
        '\x{26E9}',
        '\x{274C}',
        '\x{274E}',
        '[\x{2753}-\x{2755}]',
        '\x{2757}',
        '[\x{2763}-\x{2764}]',
        '[\x{2795}-\x{2797}]',
        '\x{27A1}',
        '\x{27B0}',
        '\x{27BF}',
        '[\x{2934}-\x{2935}]',
        '[\x{2B05}-\x{2B07}]',
        '[\x{2B1B}-\x{2B1C}]',
        '\x{2B50}',
        '\x{2B55}',
        '\x{3030}',
        '\x{303D}',
        '\x{3297}',
        '\x{3299}',
    ];

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (!is_string($input)) {
            return false;
        }

        return preg_match('/' . implode('|', self::RANGES) . '/mu', $input) === 0;
    }
}
