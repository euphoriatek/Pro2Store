<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\VueFields;

use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;


// no direct access
defined('_JEXEC') or die('Restricted access');

class LanguageSelect implements Field
{

	public static $name = 'LanguageSelect';
	public static $id = 'languageselect';
	public $type;

	public static function get(string $formId): string
	{
		$html = array();

		$languages = LanguageHelper::getContentLanguages();

		$html[] = self::getLabel();
		$html[] = '<select id="' . $formId . '" class="uk-select" v-model="form.' . $formId . '">';
		$html[] = '<option value="*">' . Text::_('JALL') . '</option>';
		foreach ($languages as $language) {
			$html[] = '<option value="'.$language->lang_code.'">' . $language->title . '</option>';
		}

		$html[] = '</select>';

		return implode('', $html);
	}

	public static function getLabel(): string
	{
		return Text::_('COM_PROTOSTORE_EMAILMANAGER_TABLE_EMAIL_LANGUAGE');
	}
}
