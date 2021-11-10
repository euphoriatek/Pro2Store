<?php
/**
 * @package     Pro2Store - Customer Addresses
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;


if(Factory::getUser()->guest) {
    return;
}

$config = \Protostore\Config\ConfigFactory::get();
$customer = \Protostore\Customer\CustomerFactory::get();
$countries = \Protostore\Country\CountryFactory::getList(0,0, true);

$addresses = $customer->addresses;


/** @var $params */
require JModuleHelper::getLayoutPath('mod_protostorecustomeraddresses', $params->get('layout', 'default'));
