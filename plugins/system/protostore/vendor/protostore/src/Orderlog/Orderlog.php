<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Orderlog;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;
use stdClass;

class Orderlog
{

    protected $db;

    public $id;
    public $order_id;
    public $note;
    public $created_by;
    public $created;


    public function __construct($orderlogid, $order_id = null, $note = null)
    {
        $this->db = Factory::getDbo();

        if ($orderlogid) {
            $this->initOrder($orderlogid);
        } else {
            $this->newLog($order_id, $note);
        }


    }


    private function initOrder($orderlogid)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_order_log'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($orderlogid));

        $this->db->setQuery($query);

        $orderlog = $this->db->loadObject();

        $this->id = $orderlog->id;
        $this->order_id = $orderlog->order_id;
        $this->note = $orderlog->note;
        $this->created_by = $orderlog->created_by;
        $this->created = $orderlog->created;


    }


    private function newLog($order_id, $note)
    {

        $this->id = 0;
        $this->order_id = $order_id;
        $this->note = $note;
        $this->created_by = Factory::getUser()->id;
        $this->created = Utilities::getDate();

        $this->create();

    }

    /**
     *
     * function save()
     *
     *
     * @since 1.0
     *
     */


    public function save()
    {

        $object = new stdClass();
        $object->id = $this->id;
        $object->order_id = $this->order_id;
        $object->note = $this->note;
        $object->created_by = $this->created_by;
        $object->created = $this->created;


        $result = $this->db->updateObject('#__protostore_order_log', $object, 'id');
    }

    /**
     *
     * function create()
     *
     *
     * @since 1.0
     *
     */


    public function create()
    {

        $object = new stdClass();
        $object->id = $this->id;
        $object->order_id = $this->order_id;
        $object->note = $this->note;
        $object->created_by = $this->created_by;
        $object->created = $this->created;


        $this->db->insertObject('#__protostore_order_log', $this);
    }

}
