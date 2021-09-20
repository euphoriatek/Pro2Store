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

$data = $displayData;

?>

<div class="uk-card uk-card-<?= $data['cardStyle']; ?> uk-margin-bottom uk-animation-fade" >
    <div class="uk-card-header">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-expand">
                <h3>
					<?= Text::_($data['cardTitle']); ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="uk-card-body">


        <table
                class="uk-table uk-table-striped uk-table-hover uk-table-responsive">
            <thead></thead>
            <thead>
            <tr>
                <th> Order Number</th>

                <th> Customer</th>
                <th> Status</th>
                <th> Date</th>
                <th> Total</th>
            </tr>
            </thead>

            <tbody class="uk-animation-fade">

            <tr v-for="order in form.jform_orders">
                <td>
                    <div class="name">
                        <a :href="'index.php?option=com_protostore&view=order&id=' + order.id">{{order.order_number}}</a>
                    </div>
                </td>
                <td> {{order.customer_name}}</td>
                <td><span :class="'uk-label uk-label-'+ order.order_status.toLowerCase()">{{order.order_status_formatted}}</span></td>
                <td> {{order.order_date}}</td>
                <td> {{order.order_total_formatted}}</td>
            </tr>
            </tbody>
        </table>


    </div>
    <div class="uk-card-footer"></div>
</div>
