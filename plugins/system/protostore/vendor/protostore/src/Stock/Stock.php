<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
// no direct access
namespace Protostore\Stock;
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class Stock
{

    private $db;

    public function __construct()
    {

        $this->db = Factory::getDbo();

    }




}
