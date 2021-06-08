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
class JFormFieldRadio extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'radio';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{


		$format = '<input type="radio" id="%1$s" name="%2$s" value="%3$s" %4$s />';
		$alt    = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->name);
		$html   = array();

		$html[] = '<fieldset id="' . $this->id . '" class="' . $this->class . ' radio">';

		if (!empty($this->element->option))
		{
			$i = 1;
			foreach ($this->element->option as $option)
			{
				$checked       = ($i == $this->value) ? 'checked="checked"' : '';
				$option->class = !empty($option->class) ? $option->class : '';
				$option->class = trim($option->class);
				$optionClass   = !empty($option->class) ? 'class="' . $option->class . '"' : '';
				$vModel   = ' v-model="'.$this->id.'"';
				$vModelVal   = ' v-bind:value="'.$i.'"';

				$oid        = $this->id . $i;
				$ovalue     = htmlspecialchars($i, ENT_COMPAT, 'UTF-8');
				$attributes = array_filter(array($checked, $optionClass, $vModel, $vModelVal));

				$html[] = sprintf($format, $oid, $this->name, $ovalue, implode(' ', $attributes));
				$html[] = '<label for="' . $oid . '" ' . trim($optionClass) . '>' . \Joomla\CMS\Language\Text::_($option) . '</label>';
				$i--;
			}
		}

		$html[] = '</fieldset>';

		return implode('', $html);

	}


}
