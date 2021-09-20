<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Setup;
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class Setup
{

	private $db;
	public $issetup;


	public function __construct()
	{
		$this->db = Factory::getDbo();

		$this->initSetup();


	}

	private function initSetup()
	{

		$query = $this->db->getQuery(true);

		$query->select('*');
		$query->from($this->db->quoteName('#__protostore_setup'));
		$query->where($this->db->quoteName('id') . ' = 1');
		$query->where($this->db->quoteName('value') . ' = ' . $this->db->quote('true'));

		$this->db->setQuery($query);

		if ($this->db->loadResult())
		{
			$this->issetup = 'true';
		}
		else
		{
			$this->issetup = 'false';
		}


	}

	public function setupComplete()
	{

		$object = new stdClass();
		$object->id = 1;
		$object->value = 'true';

		$this->db->updateObject('#__protostore_setup', $object, 'id');

		$this->issetup = true;
	}

}
