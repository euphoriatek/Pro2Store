<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Language;

// no direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;


class LanguageFactory
{

	public static function load(): ?\Joomla\CMS\Language\Language
	{

		$language = Factory::getLanguage();
		$language->load('com_protostore', JPATH_ADMINISTRATOR);

		return $language;

	}


}
