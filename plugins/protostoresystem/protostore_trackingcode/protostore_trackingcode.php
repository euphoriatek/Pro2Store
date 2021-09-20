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
