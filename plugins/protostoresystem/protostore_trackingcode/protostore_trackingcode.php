<?php
/**
 * @package     Pro2Store - TrackingCode
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

use Joomla\CMS\Factory;
use Protostore\Order\Order;
use Protostore\Orderlog\Orderlog;

class plgProtostoresystemProtostore_trackingcode extends JPlugin
{

    private $db;
    private $order;

    public function onGetTrackingCode($orderId)
    {

    }

    public function onSaveTrackingCode($orderId, $code, $type)
    {

    }





}
