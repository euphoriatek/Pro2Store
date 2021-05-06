<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class ProductFieldCore extends JPlugin
{


    public function renderTab($tabtext, $tabicon)
    {

        $html = '';
        $html .= '<li><a href="#">' . $tabtext . ' <i class="fad ' . $tabicon . '"></i></a></li>';

        return $html;

    }

}
