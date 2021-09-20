<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

// init vars
/** @var array $vars */

?>
<div id="p2s_dashboard">

    <div class="uk-grid uk-grid-match" uk-grid>

		<?php if (!$vars['products']) : ?>
            <div class="uk-width-1-1">

                <div class="uk-text-center">
                    <a href="index.php?option=com_protostore&view=product"
                       class="uk-button uk-button-large uk-button-primary"> <?= Text::_('COM_PROTOSTORE_CREATE_FIRST_PRODUCT'); ?></a>
                </div>

            </div>
		<?php endif; ?>
        <div class="uk-width-1-2">

			<?= LayoutHelper::render('dashboard/latest_orders', array(
				'orders' => $vars['orders']
			)); ?>


        </div>
        <div class="uk-width-1-2">

			<?= LayoutHelper::render('dashboard/charts/sales', array(
				'currencysymbol' => $vars['currencysymbol'],
				'monthsSales'    => $vars['monthsSales'],
				'months'         => $vars['months'],
			)); ?>

        </div>
    </div>
    <div class="uk-grid uk-grid-match" uk-grid>
        <div class="uk-width-1-2 uk-first-column">
			<?= LayoutHelper::render('dashboard/order_stats', array(
				'orderStats' => $vars['orderStats']
			)); ?>

        </div>
        <div class="uk-width-1-4 uk-first-column">

			<?= LayoutHelper::render('dashboard/bestsellers', array(
				'bestSellers' => $vars['bestSellers']
			)); ?>

        </div>
        <div class="uk-width-1-4 uk-first-column">

			<?= LayoutHelper::render('dashboard/charts/sales_in_category', array(
				'categories'    => $vars['categories'],
				'colours'       => $vars['colours'],
				'categorySales' => $vars['categorySales'],
			)); ?>

        </div>
    </div>
</div>
<script>


</script>
