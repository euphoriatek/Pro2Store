<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Faker\Provider\ka_GE;

class Company extends \Faker\Provider\Company
{
    protected static $companyPrefixes = array(
        'შპს', 'შპს', 'შპს', 'სს', 'სს', 'სს', 'კს', 'სს კორპორაცია', 'იმ', 'სპს', 'კოოპერატივი'
    );

    protected static $companyNameSuffixes = array(
        'საბჭო', 'ექსპედიცია', 'პრომი', 'კომპლექსი', 'ავტო', 'ლიზინგი', 'თრასთი', 'ეიდი', 'პლუსი',
        'ლაბი', 'კავშირი', ' და კომპანია'
    );

    protected static $companyElements = array(
        'ცემ', 'გეო', 'ქარ', 'ქიმ', 'ლიფტ', 'ტელე', 'რადიო', 'ტრანს', 'ალმას', 'მეტრო',
        'მოტორ', 'ტექ', 'სანტექ', 'ელექტრო', 'რეაქტო', 'ტექსტილ', 'კაბელ', 'მავალ', 'ტელ',
        'ტექნო'
    );

    protected static $companyNameFormats = array(
        '{{companyPrefix}} {{companyNameElement}}{{companyNameSuffix}}',
        '{{companyPrefix}} {{companyNameElement}}{{companyNameElement}}{{companyNameSuffix}}',
        '{{companyPrefix}} {{companyNameElement}}{{companyNameElement}}{{companyNameElement}}{{companyNameSuffix}}',
        '{{companyPrefix}} {{companyNameElement}}{{companyNameElement}}{{companyNameElement}}{{companyNameSuffix}}',
    );


    /**
     * @example 'იმ ელექტროალმასგეოსაბჭო'
     */
    public function company()
    {
        $format = static::randomElement(static::$companyNameFormats);

        return $this->generator->parse($format);
    }

    public static function companyPrefix()
    {
        return static::randomElement(static::$companyPrefixes);
    }

    public static function companyNameElement()
    {
        return static::randomElement(static::$companyElements);
    }

    public static function companyNameSuffix()
    {
        return static::randomElement(static::$companyNameSuffixes);
    }
}
