<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;


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


use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Router\Route;

use Protostore\Language\LanguageFactory;
use Protostore\Cart\CartFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Total\Total;
use Protostore\Config\ConfigFactory;

LanguageFactory::load();

$params = ConfigFactory::get();

$currentCartId = CartFactory::getCurrentCartId();
$cart          = CartFactory::get();
$cartItems     = $cart->cartItems;
$count         = $cart->count;


$totalType = $params->get('total_type', 'grandtotal');

switch ($totalType)
{
	case 'grandtotal' :

		if ($params->get('add_default_country_tax_to_price', '1') == "1")
		{
			$total = $cart->totalWithTax;
		}
		else
		{
			$total = $cart->total;
		}


		break;
	case 'subtotal' :
		if ($params->get('add_default_country_tax_to_price', '1') == "1")
		{
			$total = $cart->subtotalWithTax;
		}
		else
		{
			$total = $cart->subtotal;
		}
		break;
}


$checkoutLink = Route::_("index.php?Itemid={$params['cartmenuitem']}");

require ModuleHelper::getLayoutPath('mod_protostorecart', $params->get('layout', 'default'));
