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

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

use Protostore\Language\LanguageFactory;
use Protostore\Cart\CartFactory;
use Protostore\Config\ConfigFactory;
use Protostore\Currency\CurrencyFactory;

LanguageFactory::load();

$cParams = ConfigFactory::get();

$currentCartId = CartFactory::getCurrentCartId();
$cart          = CartFactory::get();
$cartItems     = $cart->cartItems;
$count         = $cart->count;
$currency      = CurrencyFactory::getCurrent();
$locale        = Factory::getLanguage()->get('tag');

/** @var  $params */
$totalType = $params->get('total_type', 'grandtotal');

switch ($totalType)
{
	case 'grandtotal' :

		if ($cParams->get('add_default_country_tax_to_price', '1') == "1")
		{
			$total = $cart->totalWithTax;
		}
		else
		{
			$total = $cart->total;
		}


		break;
	case 'subtotal' :
		if ($cParams->get('add_default_country_tax_to_price', '1') == "1")
		{
			$total = $cart->subtotalWithTax;
		}
		else
		{
			$total = $cart->subtotal;
		}
		break;
}

$checkoutLink = "index.php?Itemid=" . $params->get('cartmenuitem');

require ModuleHelper::getLayoutPath('mod_protostorecart', $params->get('layout', 'default'));
