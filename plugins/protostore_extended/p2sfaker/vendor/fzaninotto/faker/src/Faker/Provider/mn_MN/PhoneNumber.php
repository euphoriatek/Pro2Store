<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Faker\Provider\mn_MN;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{
    protected static $formats = array(
        '9#######',
        '8#######',
        '7#######',
        '3#####'
    );
}
