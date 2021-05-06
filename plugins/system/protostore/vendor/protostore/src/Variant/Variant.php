<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Variant;
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;


class Variant
{
    protected $db;

    public $variant_id;
    public $product_id;
    public $joomla_item_id;
    public $name;
    public $type;
    public $options;
    public $isDefault;


    public function __construct($variant_id)
    {
        $this->db = Factory::getDbo();
        $this->variant_id = $variant_id;
        $this->initVariant();
    }

    private function initVariant()
    {


        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_variant'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($this->variant_id));

        $this->db->setQuery($query);

        $result = $this->db->loadObject();

        $this->product_id = $result->product_id;
        $this->joomla_item_id = $result->joomla_item_id;
        $this->name = $result->name;
        $this->type = $result->type;
        $this->options = explode(',', $result->options);
        $this->isDefault = $this->setDefault();


    }

    private function setDefault()
    {

        $query = $this->db->getQuery(true);

        $query->select('name');
        $query->from($this->db->quoteName('#__protostore_variant_values'));
        $query->where($this->db->quoteName('joomla_item_id') . ' = ' . $this->db->quote($this->joomla_item_id));
        $query->where($this->db->quoteName('default') . ' = 1');

        $this->db->setQuery($query);

        $result = $this->db->loadColumn();

        $results = explode(' / ', $result[0]);


        foreach ($results as $result) {
            if (in_array($result, $this->options)) {
                return $result;
            }

        }


    }


}
