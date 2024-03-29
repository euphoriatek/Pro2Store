<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Faker\Provider\sl_SI;

class Company extends \Faker\Provider\Company
{
    protected static $formats = array(
        '{{firstName}} {{lastName}} s.p.',
        '{{lastName}} {{companySuffix}}',
        '{{lastName}}, {{lastName}} in {{lastName}} {{companySuffix}}',
    );

    protected static $companySuffix = array('d.o.o.', 'd.d.', 'k.d.', 'k.d.d.','d.n.o.','so.p.');
}
