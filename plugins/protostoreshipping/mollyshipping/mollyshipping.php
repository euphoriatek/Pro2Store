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

use Protostore\Shipping\Shipping;


class plgProtostoreshippingMollyshipping extends JPlugin
{


    public function onCalculateShipping($integer, $float)
    {

//        return '$10';

        if($integer) {
            return '1000';
        }

        return '$10';



    }


}
