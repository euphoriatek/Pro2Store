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

class FieldFactory
{

	/**
	 * @param   string  $type
	 * @param   string  $formId
	 *
	 * @return string
	 *
	 * @since 2.0
	 */

	public static function get(string $type, string $formId, string $label): string
	{

		$nameSpace = self::getNamespaceForField($type);

		return call_user_func_array($nameSpace .'::get', array('formId' => $formId, 'label' => $label));


	}

	/**
	 * @param   string  $type
	 *
	 * @return string
	 *
	 * @since 2.0
	 */

	private static function getNamespaceForField(string $type): string
	{
		$type = strtolower($type);

		return "Protostore\VueFields\\" . ucfirst($type);
	}
}
