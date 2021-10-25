<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\VueFields;


// no direct access
defined('_JEXEC') or die('Restricted access');

class Baseprice implements Field
{

	public static $name = 'Baseprice';
	public static $id = 'baseprice';

	public static function get(): string
	{

		$html = array();

		$html[] = self::getLabel();
		$html[] = '<p-inputnumber @input="getSellPrice()" mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale" name="' . self::$name . '" v-model="form.' . self::$id . '" id="' . self::$id . '">';
		$html[] = '</p-inputnumber> ';

		return implode('', $html);


	}

	public static function getLabel(): string
	{
		$html = array();


		return implode('', $html);
	}
}
