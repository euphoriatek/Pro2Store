<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\VueFields;

use Joomla\CMS\Language\Text;

// no direct access
defined('_JEXEC') or die('Restricted access');

class FlatFee implements Field
{

	public static $name = 'Flatfee';
	public static $id = 'flatfee';


	public static function get(): string
	{

		$html = array();

		$html[] = self::getLabel();
		$html[] = '<div v-show="form.jform_shipping_mode == \'flat\'">';
		$html[] = '<p-inputnumber mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"  name="' . self::$name . '" v-model="form.' . self::$id . '" id="' . self::$id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '</div> ';

		return implode('', $html);
	}

	public static function getLabel(): string
	{
		$html = array();
		$html[] = Text::_('COM_PROTOSTORE_ADD_PRODUCT_SHIPPING_FLAT_FEE');

		return implode('', $html);
	}
}
