<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access
namespace Protostore\Emailer;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use stdClass;

class Emailer
{

    protected $db;

    public $order_status;

    public function __construct($order_status)
    {
        $this->db = Factory::getDbo();
        $this->initMailer($order_status);
    }


    private function initMailer($order_status)
    {

        $this->order_status = $order_status;

    }


    public function send() {

    }



}
