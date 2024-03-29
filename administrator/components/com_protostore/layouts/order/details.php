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

/** @var array  $displayData */
$data  = $displayData;
/** @var \Protostore\Order\Order $order */
$order = $data['item'];

?>



<div class="uk-card uk-card-<?= $data['cardStyle']; ?> uk-margin-bottom">
    <div class="uk-card-header">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-expand">
                <h3>
					<?= Text::_($data['cardTitle']); ?>
                </h3>
            </div>
            <div class="uk-width-auto">
                <button @click="copyOrderToClipboard()" type="button" class="uk-icon-button" uk-icon="copy" uk-tooltip="Copy Order Details"></button>
            </div>

        </div>
    </div>

    <div class="uk-card-body">

        <table class="uk-table uk-table-striped uk-table-hover">
            <tbody>
            <tr>
                <td class="uk-text-nowrap uk-table-shrink">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_NUMBER'); ?>:</div>
                </td>
                <td>
                    <div class="uk-panel"><?= $order->order_number; ?></div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                            <span @click="copyToClipboard('<?= $order->order_number; ?>')" style="cursor: pointer;">
                              <i class="fal fa-copy"></i>
                            </span>
                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_DATE'); ?>:</div>
                </td>
                <td>
                    <div id="ordered_date" class="el-content uk-panel"> <?= $order->order_date; ?>
                    </div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_ORDER_STATUS'); ?>:</div>
                </td>
                <td>
                    <div id="order_status" class="el-content uk-panel">
                        <div class="uk-grid">
                            <div class="uk-width-expand">
                                  <span
                                          :class="'uk-label uk-label-'+ order.order_status.toLowerCase()">{{order.order_status_formatted}}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">

                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_PAID'); ?>:</div>
                </td>
                <td>
                    <div class="el-content uk-panel">
                      <span v-show="order.order_paid === 1" @click="togglePaid" style="font-size: 18px; color: green; cursor: pointer;">
                          <i class="fal fa-check-circle"></i>
                      </span>
                        <span v-show="order.order_paid === 0" @click="togglePaid" style="font-size: 18px; color: red; cursor: pointer;">
                        <i class="fal fa-times-circle"></i>
                      </span>
                    </div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_SUBTOTAL'); ?>:</div>
                </td>
                <td>
                    <div class="el-content uk-panel"><?= $order->order_subtotal_formatted; ?></div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_SHIPPING_TOTAL'); ?>:</div>
                </td>
                <td>
                    <div class="el-content uk-panel"><?= $order->shipping_total_formatted; ?></div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                </td>
            </tr>
			<?php if ($order->discount_total > 0) : ?>
                <tr>
                    <td class="uk-text-nowrap">
                        <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_DISCOUNT_TOTAL'); ?>:</div>
                    </td>
                    <td>
                        <div class="el-content uk-panel"><?= $order->discount_total_formatted; ?>
                            (<?= $order->discount_code; ?>)
                        </div>
                    </td>
                    <td class="uk-text-nowrap uk-table-shrink">
                    </td>
                </tr>
			<?php endif; ?>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_TAX_TOTAL'); ?>:</div>
                </td>
                <td>
                    <div class="el-content uk-panel"><?= $order->tax_total_formatted; ?>
                    </div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_GRAND_TOTAL'); ?>:</div>
                </td>
                <td>
                    <div class="el-content uk-panel"><?= $order->order_total_formatted; ?></div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_PAYMENT_METHOD'); ?>:</div>
                </td>
                <td>
                    <div id="payment_method" class="el-content uk-panel"><img
                                src="<?= $order->payment_method_icon; ?>"
                        />
						<?= $order->payment_method; ?>
						<?= $order->vendor_token; ?></div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                </td>
            </tr>
            <tr>
                <td class="uk-text-nowrap">
                    <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_CUSTOMER_NOTES'); ?>:</div>
                </td>
                <td>
                    <div id="usernotes" class="el-content uk-panel"><?= $order->customer_notes; ?> </div>
                </td>
                <td class="uk-text-nowrap uk-table-shrink">
                    <ul class="uk-iconnav uk-flex-right">
                        <li uk-tooltip="Copy Text" title="" aria-expanded="false">
                            <a @click=""><i class="fal fa-copy"></i></a>
                        </li>
                    </ul>
                </td>
            </tr>

                <tr v-show="order.tracking_link">
                    <td class="uk-text-nowrap">
                        <div class="el-title"><?= Text::_('COM_PROTOSTORE_ORDER_TRACKING_CODE'); ?>:</div>
                    </td>
                    <td>
                        <div class="el-content uk-panel">
                            <a :href="order.tracking_link"  target="_blank">{{order.tracking_code}}
                                <i class="fal fa-external-link"></i>
                            </a>
                        </div>
                    </td>

                </tr>

            </tbody>
        </table>

    </div>


    <div class="uk-card-footer"></div>
</div>
