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

interface Field
{

	public static function get(string $formId, string $label): string;
}
