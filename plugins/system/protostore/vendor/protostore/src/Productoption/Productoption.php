<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Productoption;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;

class Productoption
{

    private $db;

    public $id;
    public $product_id;
    public $optiontypeid;
    public $optiontypename;
    public $optionname;
    public $modifier;
    public $modifiertype;
    public $modifiervalue;
    public $optionsku;


    public function __construct($optionid)
    {

        $this->db = Factory::getDbo();
        $this->initOption($optionid);
    }


    private function initOption($optionid)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_product_option_values'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($optionid));

        $this->db->setQuery($query);

        $option = $this->db->loadObject();

        if($option) {
	        $this->id = $optionid;
	        $this->product_id = $option->product_id;
	        $this->optiontypeid = $option->optiontype;
	        $this->optiontypename = $this->getOptionTypeName();
	        $this->optionname = $option->optionname;
	        $this->modifier = $option->modifier;
	        $this->modifiertype = $option->modifiertype;
	        $this->modifiervalue = $option->modifiervalue;
	        $this->optionsku = $option->optionsku;
        }
        else {
        	return false;
        }



    }

    private function getOptionTypeName()
    {
        $query = $this->db->getQuery(true);

        $query->select('name');
        $query->from($this->db->quoteName('#__protostore_product_option'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($this->optiontypeid));

        $this->db->setQuery($query);

        return $this->db->loadResult();
    }


}
