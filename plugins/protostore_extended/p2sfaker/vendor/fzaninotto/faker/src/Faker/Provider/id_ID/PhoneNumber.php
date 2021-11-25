<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Faker\Provider\id_ID;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{
    protected static $formats = array(
        // regional numbers
        '02# #### ###',
        '02## #### ###',
        '03## #### ###',
        '04## #### ###',
        '05## #### ###',
        '06## #### ###',
        '07## #### ###',
        '09## #### ###',

        '02# #### ####',
        '02## #### ####',
        '03## #### ####',
        '04## #### ####',
        '05## #### ####',
        '06## #### ####',
        '07## #### ####',
        '09## #### ####',

        // mobile numbers
        '08## ### ###',   // 0811 XXX XXX, 10 digits, very old
        '08## #### ###',  // 0811 XXXX XXX, 11 digits
        '08## #### ####', // 0811 XXXX XXXX, 12 digits

        // international numbers
        '(+62) 8## ### ###',

        '(+62) 2# #### ###',
        '(+62) 2## #### ###',
        '(+62) 3## #### ###',
        '(+62) 4## #### ###',
        '(+62) 5## #### ###',
        '(+62) 6## #### ###',
        '(+62) 7## #### ###',
        '(+62) 8## #### ###',
        '(+62) 9## #### ###',

        '(+62) 2# #### ####',
        '(+62) 2## #### ####',
        '(+62) 3## #### ####',
        '(+62) 4## #### ####',
        '(+62) 5## #### ####',
        '(+62) 6## #### ####',
        '(+62) 7## #### ####',
        '(+62) 8## #### ####',
        '(+62) 9## #### ####',
    );
}
