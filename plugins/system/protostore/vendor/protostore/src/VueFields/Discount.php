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

class Discount implements Field
{

	public static $name = 'Discount';
	public static $id = 'discount';


	public static function get(): string
	{
		$html = array();


		$html[] = self::getLabel();
		$html[] = '<div v-show="form.jform_show_discount">';
		$html[] = '<div class="uk-grid" uk-grid>';
		$html[] = '<div class="uk-width-auto">';
		$html[] = '<p-inputnumber @input="getSellPrice()" v-show="form.jform_discount_type == 1" mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"  name="' . self::$name . '" v-model="form.' . self::$id . '" id="' . self::$id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '<p-inputnumber @input="getSellPrice()"  v-show="form.jform_discount_type == 2" mode="decimal" name="' . self::$name . '" v-model="form.' . self::$id . '" id="' . self::$id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '</div>';
		$html[] = '<div class="uk-width-expand">';
		$html[] = '<select @change="getSellPrice()" class="uk-select" v-model="form.jform_discount_type">';
		$html[] = '<option value="1">'.Text::_('COM_PROTOSTORE_ADD_PRODUCT_DISCOUNT_AMOUNT').'</option>';
		$html[] = '<option value="2">'.Text::_('COM_PROTOSTORE_ADD_PRODUCT_DISCOUNT_PERCENT').'</option>';
		$html[] = '</select>';
		$html[] = '</div>';
		$html[] = '</div>';
		$html[] = '<br/><span>'. Text::_('COM_PROTOSTORE_ADD_PRODUCT_DISCOUNT_SELL_PRICE_MESSAGE').' {{sellPrice}}</span>';
		$html[] = ' </div>';


		return implode('', $html);
	}

	public static function getLabel(): string
	{
		return '<div v-show="form.jform_show_discount">' . Text::_('COM_PROTOSTORE_ADD_PRODUCT_DISCOUNT') . '</div>';
	}
}
