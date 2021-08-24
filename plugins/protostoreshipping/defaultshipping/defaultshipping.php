<?php
/**
 * @package     Pro2Store - Default Shipping
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

use Joomla\CMS\Factory;

use Protostore\Cart\Cart;
use Protostore\Shipping\Shipping;
use Protostore\Total\TotalFactory;
use Protostore\Shipping\ShippingFactory;


class plgProtostoreshippingDefaultshipping extends JPlugin


{
    protected $autoloadLanguage = true;

    function __construct(&$subject, $config)
    {
        $language = Factory::getLanguage();
        $language->load('com_protostore', JPATH_ADMINISTRATOR);
        parent::__construct($subject, $config);

    }


    public function onCalculateShippingdefaultshipping(Cart $cart)
    {


//    	return 0;

        if ($this->params->get('threshold_enable')) {
            $threshold = $this->params->get('threshold_value');
            $threshold = preg_replace("/[^0-9]/", "", $threshold);


            if ($cart->subtotalInt > ($threshold * 100)) {
                return 0;
            }


        }


        if ($this->params->get('order_flat_enable')) {

            return ($this->params->get('order_flat_value') * 100);

        }

        if ($this->params->get('capping_enable')) {

            switch ($this->params->get('capping_type')) {
                case 'value' :
                    $shippingTotal = ShippingFactory::calculateTotalShipping($cart);
                    $cap = ($this->params->get('capping_value') * 100);
                    if ($shippingTotal >= $cap) {
                        return $cap;
                    }
                    break;
                case 'expensive' :
                    return Shipping::calculateMostExpensiveItemShipping();

            }

        }


        return ShippingFactory::calculateTotalShipping($cart);


    }


}
