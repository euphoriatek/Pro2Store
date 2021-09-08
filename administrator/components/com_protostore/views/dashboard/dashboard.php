<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

// init vars
/** @var array $vars */

?>
<div id="p2s_dashboard">
    <div class="uk-grid uk-grid-match" uk-grid>
        <div class="uk-width-1-2">

	        <?= LayoutHelper::render('dashboard/latest_orders', array(
		        'orders'      => $vars['orders']
	        )); ?>


        </div>
        <div class="uk-width-1-2">

	        <?= LayoutHelper::render('dashboard/charts/sales', array(
		        'currencysymbol'      => $vars['currencysymbol'],
		        'monthsSales'      => $vars['monthsSales'],
		        'months'      => $vars['months'],
	        )); ?>

        </div>
    </div>
    <div class="uk-grid uk-grid-match" uk-grid>
        <div class="uk-width-1-2 uk-first-column">
	        <?= LayoutHelper::render('dashboard/order_stats', array(
		        'orderStats'      => $vars['orderStats']
	        )); ?>

        </div>
        <div class="uk-width-1-4 uk-first-column">

        <?= LayoutHelper::render('dashboard/bestsellers', array(
		        'bestSellers'      => $vars['bestSellers']
	        )); ?>

        </div>
        <div class="uk-width-1-4 uk-first-column">

	        <?= LayoutHelper::render('dashboard/charts/sales_in_category', array(
		        'categories'      => $vars['categories'],
		        'colours'      => $vars['colours'],
		        'categorySales'      => $vars['categorySales'],
	        )); ?>

        </div>
    </div>
</div>
<script>


</script>
