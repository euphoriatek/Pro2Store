<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
use Joomla\CMS\Language\Text;
use Joomla\CMS\Language\LanguageHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Clicks field.
 *
 * @since  1.6
 */
class JFormFieldLanguageselect extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Languageselect';

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


		$languages = LanguageHelper::getContentLanguages();


		$html[] = '<select class="uk-select" v-model="form.' . $this->id . '">';
		$html[] = '<option value="*">' . Text::_('JALL') . '</option>';
		foreach ($languages as $language) {
			$html[] = '<option value="'.$language->lang_code.'">' . $language->title . '</option>';
		}

		$html[] = '</select>';

		return implode('', $html);


	}
}
