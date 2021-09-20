<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


namespace Protostore\Product;
// no direct access
defined('_JEXEC') or die('Restricted access');


use stdClass;

class Customfield
{


	public $id;
	public $context;
	public $group_id;
	public $title;
	public $name;
	public $label;
	public $default_value;
	public $type;
	public $description;
	public $state;
	public $ordering;
	public $params;
	public $fieldparams;

	public $options;
	public $value;


	public function __construct($data, $itemid)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($itemid);
		}

	}

	/**
	 *
	 * Function to simply "hydrate" the database values directly to the class parameters.
	 *
	 * @param $data
	 *
	 *
	 * @since 1.6
	 */

	private function hydrate($data)
	{
		foreach ($data as $key => $value)
		{

			if (property_exists($this, $key))
			{
				$this->{$key} = $value;
			}

		}
	}

	/**
	 *
	 * Function to "hydrate" all non-database values.
	 *
	 * @param $itemid
	 *
	 * @since 1.6
	 */

	private function init($itemid)
	{

		$this->type = $this->setType($this->type, $this->fieldparams);

		if ($this->type == 'list' || $this->type == 'radio')
		{
			$this->options = $this->setOptions();
		}
		else
		{
			$this->options = [];
		}

		if ($itemid)
		{
			$this->value = ProductFactory::setCustomFieldValue($this->id, $itemid);
		}
		else
		{
			$this->value = null;
		}

	}


	/**
	 *
	 * @return array
	 *
	 * @since 1.6
	 */


	private function setOptions(): array
	{

		$params = json_decode($this->fieldparams);


		$options = $params->options;

		$availableOptions = array();

		foreach ($options as $option)
		{
			$availableOptions[] = $option;
		}

		return $availableOptions;
	}

	/**
	 * @param   string  $type
	 * @param   string  $fieldParams
	 *
	 * @return string
	 *
	 * @since 1.6
	 */

	private function setType(string $type = 'text', string $fieldParams): string
	{

		$fieldParams = json_decode($fieldParams);

		if (isset($fieldParams->filter))
		{

			if ($fieldParams->filter === 'integer')
			{
				return 'number';
			}

			if ($fieldParams->filter === 'tel')
			{
				return 'tel';
			}
			if ($fieldParams->filter === 'float')
			{
				return 'number';
			}

		}

		return $type;


	}


	public static function save($field, $itemid)
	{

		// delete all records

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('field_id') . ' = ' . $db->quote($field->id),
			$db->quoteName('item_id') . ' = ' . $db->quote($itemid)
		);

		$query->delete($db->quoteName('#__fields_values'));
		$query->where($conditions);

		$db->setQuery($query);
		$db->execute();


		// now recreate if there is a value set

		if ($field->value)
		{
			$object           = new stdClass();
			$object->field_id = $field->id;
			$object->item_id  = $itemid;
			$object->value    = $field->value;

			$db->insertObject('#__fields_values', $object);
		}


	}

}
