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
class JFormFieldDiscount extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Discount';


	public function getLabel()
	{
		return '<div v-show="form.jform_show_discount">' . $this->element['label'] . '</div>';
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


		$html[] = '<div v-show="form.jform_show_discount">';
		$html[] = '<p-inputnumber mode="currency" :currency="p2s_currency.iso" :locale="p2s_locale"  name="' . $this->name . '" v-model="form.' . $this->id . '" id="' . $this->id . '">';
		$html[] = '</p-inputnumber> ';
		$html[] = '<br/><span>This product will sell for: {{sellPrice}}</span>';
		$html[] = ' </div>';


		return implode('', $html);


	}
}
