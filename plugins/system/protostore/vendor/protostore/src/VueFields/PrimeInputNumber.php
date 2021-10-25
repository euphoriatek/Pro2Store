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

class PrimeInputNumber implements Field
{



	public static function get(string $formId): string
	{
		$html = array();

		switch ($formId)
		{
			case 'discount_amount':

				$html[] = '<div v-show="form.jform_discount_type == 1">';
				$html[] = '<div>' . self::getLabel() . '</div>';
				$html[] = '<p-inputnumber v-model="form.' . $formId . '" mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"></p-inputnumber>';
				$html[] = '</div>';
				break;

			case 'discount_percentage':

				$html[] = '<div v-show="form.jform_discount_type == 2">';
				$html[] = '<div>' . self::getLabel() . '</div>';
				$html[] = '<p-inputnumber v-model="form.' . $formId . '"  mode="decimal" max="100" min="1" minFractionDigits="2" useGrouping="false"></p-inputnumber>';
				$html[] = '</div>';
				break;


		}


		return implode('', $html);
	}

	public static function getLabel(): string
	{
		return \Joomla\CMS\Language\Text::_('COM_PROTOSTORE_ADD_PRODUCT_DISCOUNT_LABEL');
	}
}
