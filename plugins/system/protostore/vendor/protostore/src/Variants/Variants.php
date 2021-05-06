<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Variants;
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;

use Protostore\Variant\Variant;

class Variants
{
    protected $db;

    public $item_id;
    public $variants;


    public function __construct($item_id)
    {
        $this->db = Factory::getDbo();
        $this->item_id = $item_id;
        $this->initVariants();
    }

    private function initVariants()
    {


        $query = $this->db->getQuery(true);

        $query->select('id');
        $query->from($this->db->quoteName('#__protostore_variant'));
        $query->where($this->db->quoteName('joomla_item_id') . ' = ' . $this->db->quote($this->item_id));

        $this->db->setQuery($query);

        $results = $this->db->loadColumn();


        foreach ($results as $result) {
            $this->variants[] = new Variant($result);
        }



    }


}
