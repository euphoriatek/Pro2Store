<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Faker\Provider\ko_KR;

class Company extends \Faker\Provider\Company
{
    protected static $formats = array(
        '{{companyPrefix}}{{firstName}}',
        '{{companyPrefix}}{{firstName}}{{companySuffix}}',
        '{{firstName}}{{companySuffix}}',
        '{{firstName}}{{companySuffix}}',
        '{{firstName}}{{companySuffix}}',
        '{{firstName}}{{companySuffix}}',
    );

    protected static $companyPrefix = array('(주)', '(주)', '(주)', '(유)');

    protected static $companySuffix = array(
        '전자', '건설', '식품', '인터넷', '그룹', '은행', '보험', '제약', '금융', '네트웍스', '기획', '미디어', '연구소', '모바일', '스튜디오', '캐피탈',
    );

    public static function companyPrefix()
    {
        return static::randomElement(static::$companyPrefix);
    }

    public static function companySuffix()
    {
        return static::randomElement(static::$companySuffix);
    }
}
