<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Clicks field.
 *
 * @since  1.6
 */
class JFormFieldPinputnumber extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'pinputnumber';

	public function getLabel()
	{
		return '';
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		$html = array();

		switch ($this->element['formName'])
		{
			case 'discount_amount':

				$html[] = '<div v-show="form.jform_discount_type == 1">';
				$html[] = '<div>' . $this->element['label'] . '</div>';
				$html[] = '<p-inputnumber v-model="form.' . $this->id . '" mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"></p-inputnumber>';
				$html[] = '</div>';
				break;

			case 'discount_percentage':

				$html[] = '<div v-show="form.jform_discount_type == 2">';
				$html[] = '<div>' . $this->element['label'] . '</div>';
				$html[] = '<p-inputnumber v-model="form.' . $this->id . '"  mode="decimal" max="100" min="1" minFractionDigits="2" useGrouping="false"></p-inputnumber>';
				$html[] = '</div>';
				break;


		}


		return implode('', $html);

	}
}
