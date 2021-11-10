<?php
/**
 * @package     Pro2Store - Customer Orders
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

if(Factory::getUser()->guest) {
    return;
}

use Protostore\Orders\Orders;
use Protostore\Order\Order;

Factory::getDocument()->addStyleSheet('modules/mod_protostorecustomerorders/assets/css/style.css');


$customersOrders = Orders::getOrderListByCustomer();

$orders = array();

foreach ($customersOrders as $order) {
    $orders[] = new Order($order->id);
}

if($customersOrders == false) return;

require JModuleHelper::getLayoutPath('mod_protostorecustomerorders', $params->get('layout', 'default'));
