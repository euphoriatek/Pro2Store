<?php
/**
 * @package     Pro2Store Cart Fab
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Elm House Creative. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;

if (!ComponentHelper::getComponent('com_protostore', true)->enabled) {
    return;
}

if (!PluginHelper::isEnabled('system', 'protostore')) {
    return;
}

use Joomla\CMS\Helper\ModuleHelper;

$cart = \Protostore\Cart\CartFactory::get();

$count = $cart->count;

/** @var  $params */
$checkoutLink = "index.php?Itemid=" . $params->get('cartmenuitem');

require ModuleHelper::getLayoutPath('mod_protostorecartfab', $params->get('layout', 'default'));
