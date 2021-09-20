<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace YpsApp\Source;

class SourceListener
{
    public static function initSource($source)
    {

        $source->objectType('Ypsproduct', Ypsproduct\Ypsproduct::config());
        $source->objectType('Article', SkuType\SkuType::config());
        $source->objectType('Article', BasepriceType\BasepriceType::config());
        $source->objectType('Article', DiscountpriceType\DiscountpriceType::config());
        $source->objectType('Article', StockType\StockType::config());
        $source->objectType('Article', WeightType\WeightType::config());
        $source->objectType('Article', ShippingFlatFeeType\ShippingFlatFeeType::config());
        $source->queryType(Ypsproduct\YpsCartQueryType::config());


    }


}
