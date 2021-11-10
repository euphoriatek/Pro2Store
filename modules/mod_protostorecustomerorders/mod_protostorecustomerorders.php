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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;

if (!ComponentHelper::getComponent('com_protostore', true)->enabled)
{
	return;
}

if (!PluginHelper::isEnabled('system', 'protostore'))
{
	return;
}

$customer = \Protostore\Customer\CustomerFactory::get();

if(!$customer) return;
if(!$customer->orders) return;

$orders = $customer->orders;

Factory::getDocument()->addStyleSheet('modules/mod_protostorecustomerorders/assets/css/style.css');

/** @var $params */
require JModuleHelper::getLayoutPath('mod_protostorecustomerorders', $params->get('layout', 'default'));
