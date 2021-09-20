<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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
