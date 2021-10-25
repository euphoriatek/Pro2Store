<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\VueFields;

use Protostore\VueFields\Field;


// no direct access
defined('_JEXEC') or die('Restricted access');

class SelectList extends Field
{

	public $name = 'text';
	public $id;
	public $type;

}
