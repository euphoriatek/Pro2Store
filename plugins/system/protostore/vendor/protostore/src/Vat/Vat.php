<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Vat;
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class Vat
{

    private $db;


    public function __construct($id)
    {
        $this->db = Factory::getDbo();



    }


}
