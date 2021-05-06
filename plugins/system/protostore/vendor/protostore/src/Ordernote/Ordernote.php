<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Ordernote;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;
use stdClass;

class Ordernote
{

    protected $db;

    public $id;
    public $order_id;
    public $note;
    public $created_by;
    public $created_by_name;
    public $created_by_email;
    public $created_by_avatar_abb;
    public $created;


    public function __construct($ordernoteid, $order_id = null, $note = null)
    {
        $this->db = Factory::getDbo();

        if ($ordernoteid) {
            $this->initOrder($ordernoteid);
        } else {
            $this->newNote($order_id, $note);
        }


    }


    private function initOrder($orderlogid)
    {

        $query = $this->db->getQuery(true);

        $query->select('*');
        $query->from($this->db->quoteName('#__protostore_order_notes'));
        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($orderlogid));

        $this->db->setQuery($query);

        $ordernote = $this->db->loadObject();

        $this->id = $ordernote->id;
        $this->order_id = $ordernote->order_id;
        $this->note = $ordernote->note;
        $this->created_by = $ordernote->created_by;
        $this->created_by_name = Factory::getUser($ordernote->created_by)->name;
        $this->created_by_email = Factory::getUser($ordernote->created_by)->email;
        $this->created_by_avatar_abb = $this->getAvatarAbb();
        $this->created = $ordernote->created;


    }

    private function getAvatarAbb() {


        $words = preg_split("/[\s,_-]+/", $this->created_by_name);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }

        return $acronym;
    }


    private function newNote($order_id, $note)
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


        $this->db->updateObject('#__protostore_order_notes', $object, 'id');
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


        $this->db->insertObject('#__protostore_order_notes', $this);
    }

}
