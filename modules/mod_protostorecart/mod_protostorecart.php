<?php
/**
 * @package     Pro2Store Cart
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Elm House Creative. All rights reserved.
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
use Joomla\CMS\Router\Route;

use Protostore\Language\LanguageFactory;
use Protostore\Cart\CartFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Total\Total;

LanguageFactory::load();


$currentCartId = CartFactory::getCurrentCartId();
$cart = CartFactory::get();
$cartItems = $cart->cartItems;
$count = $cart->count;


$totalType = $params->get('total_type', 'grandtotal');

switch ($totalType) {
    case 'grandtotal' :
	    $total = $cart->total;
        break;
    case 'subtotal' :
        $total = $cart->subtotal;
        break;
}


$checkoutLink = Route::_("index.php?Itemid={$params['cartmenuitem']}");

require ModuleHelper::getLayoutPath('mod_protostorecart', $params->get('layout', 'default'));
