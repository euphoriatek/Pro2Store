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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

/** @var array $vars */
$item = $vars['item'];

?>

<div id="p2s_email_form">
	<form @submit.prevent="saveItem">
        <input type="hidden" id="copytext" :value="copytext">
		<div class="uk-margin-left">
			<div class="uk-grid" uk-grid="">
				<div class="uk-width-1-1">

					<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

						<nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

							<div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo">

	                                    <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  {{form.jform_subject}}

                                </span>

							</div>

							<div class="uk-navbar-right">

								<button type="submit"
								        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
									<?= Text::_('JTOOLBAR_APPLY'); ?>
								</button>
								<button type="submit" @click="andClose = true"  class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
									<?= Text::_('JTOOLBAR_SAVE'); ?>
								</button>
								<a class="uk-button uk-button-default uk-button-small "
								   href="index.php?option=com_protostore&view=emailmanager"><?= Text::_('JTOOLBAR_CANCEL'); ?></a>

							</div>

						</nav>
					</div>

				</div>
				<div class="uk-width-2-3">

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardStyle' => 'default',
						'cardTitle' => 'COM_PROTOSTORE_ADDEMAIL_TITLE',
						'cardId'    => 'details',
						'fields'    => array('to', 'subject', 'emailtype', 'body', 'language', 'published')
					)); ?>


				</div>


				<div class="uk-width-1-3">
                    <div class="uk-card uk-card-default uk-margin-bottom">
                        <div class="uk-card-header"><h5><?= Text::_('COM_PROTOSTORE_ADDEMAIL_AVAILABLE_SHORTCODES'); ?></h5></div>
                        <div class="uk-card-body">
                        <ul uk-accordion>
                            <li>
                                <a class="uk-accordion-title"
                                   href="#"><?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_GLOBAL'); ?></a>
                                <div class="uk-accordion-content">
                                    <ul class="uk-list">
                                        <li style="cursor: pointer" @click="copyText('{site_name}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SITENAME'); ?> <span>&#123; site_name
                                &#125;</span> <i
                                                    class="fal fa-copy"></i></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a class="uk-accordion-title" href="#"><?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_INFO'); ?></a>
                                <div class="uk-accordion-content">
                                    <ul class="uk-list">
                                        <li style="cursor: pointer" @click="copyText('{order_number}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_NUMBER'); ?>
                                            <span>&#123;order_number&#125;</span> <i
                                                    class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{order_grand_total}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_GRANDTOTAL'); ?>
                                            <span>&#123;order_grand_total&#125;</span> <i
                                                    class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{order_subtotal}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_SUBTOTAL'); ?>
                                            <span>&#123;order_subtotal&#125;</span> <i
                                                    class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{order_shipping_total}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_SHIPPINGTOTAL'); ?><span>&#123;order_shipping_total&#125;</span>
                                            <i
                                                    class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{order_currency_symbol}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_CURRENCY'); ?> Symbol
                                            <span>&#123;order_currency_symbol&#125;</span> <i class="fal fa-copy"></i>
                                        </li>
                                        <li style="cursor: pointer" @click="copyText('{order_date}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_DATE'); ?>
                                            <span>&#123;order_date&#125;</span> <i
                                                    class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{order_payment_method}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_ORDER_PAYMENT_METHOD'); ?>
                                            <span>&#123;order_payment_method&#125;</span> <i class="fal fa-copy"></i></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a class="uk-accordion-title" href="#"><?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_TRACKING_INFO'); ?></a>
                                <div class="uk-accordion-content">
                                    <ul class="uk-list">
                                        <li style="cursor: pointer" @click="copyText('{tracking_code}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_TRACKING_CODE'); ?>
                                            <span>&#123;tracking_code&#125;</span> <i
                                                    class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{tracking_url}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_TRACKING_URL'); ?>
                                            <span>&#123;tracking_url&#125;</span> <i
                                                    class="fal fa-copy"></i></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a class="uk-accordion-title"
                                   href="#"><?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_CUSTOMER_INFO'); ?></a>
                                <div class="uk-accordion-content">
                                    <ul class="uk-list">
                                        <li style="cursor: pointer" @click="copyText('{customer_name}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_CUSTOMER_NAME'); ?>
                                            <span>&#123;customer_name&#125;</span>
                                            <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{customer_email}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_CUSTOMER_EMAIL'); ?>
                                            <span>&#123;customer_email&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{customer_order_count}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_CUSTOMER_ORDERCOUNT'); ?>
                                            <span>&#123;customer_order_count&#125;</span> <i class="fal fa-copy"></i></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a class="uk-accordion-title"
                                   href="#"><?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS'); ?></a>
                                <div class="uk-accordion-content">
                                    <ul class="uk-list">
                                        <li style="cursor: pointer"
                                            @click="copyText('{shipping_name},{shipping_address1}, {shipping_address2}, {shipping_address3}, {shipping_town}, {shipping_state}, {shipping_country}, {shipping_postcode}')"><?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_FULL'); ?>
                                            <span>&#123;full_shipping_address&#125;</span>
                                            <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_name}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_NAME'); ?>
                                            <span>&#123;shipping_name&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_address1}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_LINE1'); ?><span>&#123;shipping_address1&#125;</span>
                                            <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_address2}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_LINE2'); ?><span>&#123;shipping_address2&#125;</span>
                                            <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_address3}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_LINE3'); ?><span>&#123;shipping_address3&#125;</span>
                                            <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_town}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_TOWN'); ?>
                                            <span>&#123;shipping_town&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_state}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_STATE'); ?>
                                            <span>&#123;shipping_state&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_country}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_COUNTRY'); ?>
                                            <span>&#123;shipping_country&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_postcode}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_POSTCODE'); ?>
                                            <span>&#123;shipping_postcode&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_email}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_EMAIL'); ?>
                                            <span>&#123;shipping_email&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_mobile}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_MOBILE'); ?> No.
                                            <span>&#123;shipping_mobile&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{shipping_phone}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_SHIPPING_ADDRESS_PHONE'); ?><span>&#123;shipping_phone&#125;</span>
                                            <i class="fal fa-copy"></i></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a class="uk-accordion-title"
                                   href="#"><?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS'); ?></a>
                                <div class="uk-accordion-content">
                                    <ul class="uk-list">
                                        <li style="cursor: pointer"
                                            @click="copyText('{billing_name},{billing_address1}, {billing_address2}, {billing_address3}, {billing_town}, {billing_state}, {billing_country}, {billing_postcode}')"> <span>&#123;full_billing_address&#125;</span>
                                            <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_name}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_NAME'); ?>
                                            <span>&#123;billing_name&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_address1}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_LINE1'); ?>
                                            <span>&#123;billing_address1&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_address2}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_LINE2'); ?>
                                            <span>&#123;billing_address2&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_address3}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_LINE3'); ?>
                                            <span>&#123;billing_address3&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_town}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_TOWN'); ?>
                                            <span>&#123;billing_town&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_state}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_STATE'); ?>
                                            <span>&#123;billing_state&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_country}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_COUNTRY'); ?>
                                            <span>&#123;billing_country&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_postcode}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_POSTCODE'); ?>
                                            <span>&#123;billing_postcode&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_email}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_EMAIL'); ?>
                                            <span>&#123;billing_email&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_mobile}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_MOBILE'); ?>
                                            <span>&#123;billing_mobile&#125;</span> <i class="fal fa-copy"></i></li>
                                        <li style="cursor: pointer" @click="copyText('{billing_phone}')">
                                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_SHORTCODES_BILLING_ADDRESS_PHONE'); ?>
                                            <span>&#123;shipping_phone&#125;</span> <i class="fal fa-copy"></i></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                        </div>
                    </div>
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header"><h5><?= Text::_('COM_PROTOSTORE_ADDEMAIL_HELP'); ?></h5></div>
                        <div class="uk-card-body">
                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_TO_MODAL_MESSAGE'); ?>
                            <?= Text::_('COM_PROTOSTORE_ADDEMAIL_EMAIL_TYPES_MODAL_MESSAGE'); ?>
                        </div>
                        <div class="uk-card-footer"></div>
                    </div>

                </div>
			</div>

		</div>
	</form>
</div>


