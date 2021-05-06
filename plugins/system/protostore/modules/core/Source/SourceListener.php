<?php

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
