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

use Joomla\CMS\Form\FormField;

/**
 * Clicks field.
 *
 * @since  1.6
 */
class JFormFieldPinputpercentage extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'pinputpercentage';

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

		$html[] = '<div v-show="form.jform_discount_type == 2">';
		$html[] = '<div>' . $this->element['label'] . '</div>';
		$html[] = '<p-inputnumber v-show="form.jform_discount_type == 2" v-model="form.'.$this->id.'" prefix="%"></p-inputnumber>';
		$html[] = '</div>';


		return implode('', $html);


	}
}
