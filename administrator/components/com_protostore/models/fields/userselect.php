<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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
