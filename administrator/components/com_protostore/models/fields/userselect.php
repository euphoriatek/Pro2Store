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

use Protostore\Utilities\Utilities;


class JFormFieldUserselect extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Userselect';

	public function getLabel()
	{
		return \Joomla\CMS\Language\Text::_('COM_PROTOSTORE_CUSTOMER_JUSER');
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

		$users = Utilities::getUserListForSelect();


		$html  = array();

		$html[] = '<select class="uk-select" v-model="form.'.$this->id.'">';
		$html[] = '<option>--Please Select--</option>';

		foreach ($users as $user)
		{
			$html[] = '<option value="' . $user->id . '">';
			$html[] = $user->name;
			$html[] = "</option>";
		}


		$html[] = "</select>";

		return implode('', $html);

	}
}
