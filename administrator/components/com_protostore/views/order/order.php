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

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;


HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');


$item = $vars['item'];


?>

<div id="p2s_order_form">

    <div class="uk-margin-left">
        <div class="uk-grid" uk-grid="">
            <div class="uk-width-1-1">

                <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

                    <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                        <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo">

	                                    <?= Text::_('COM_PROTOSTORE_ORDER_NUMBER'); ?>:  <?= $item->order_number; ?>  <div
                                            class="uk-label uk-label-<?= strtolower($item->order_status) ?>">
                                        <?= $item->order_status_formatted; ?>
                                    </div>

                                </span>

                        </div>

                        <div class="uk-navbar-right">

                            <a class="uk-button uk-button-default uk-button-small "
                               href="index.php?option=com_protostore&view=discounts"><< Back to Orders</a>

                        </div>

                    </nav>
                </div>

            </div>
            <div class="uk-width-2-3">


                <ul uk-tab="animation: uk-animation-fade" class="uk-tab">
                    <li class="uk-active">
                        <a href="#" aria-expanded="true">
							<?= Text::_('COM_PROTOSTORE_ORDER_DETAILS'); ?>
                            <svg width="10px"
                                 class="svg-inline--fa fa-box-check fa-w-20" aria-hidden="true" focusable="false"
                                 data-prefix="fad"
                                 data-icon="box-check" role="img" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 640 512" data-fa-i2svg="">
                                <g class="fa-group">
                                    <path class="fa-secondary" fill="currentColor"
                                          d="M448 128c-106 0-192 86-192 192s86 192 192 192 192-86 192-192-86-192-192-192zm114.1 147.8l-131 130a11 11 0 0 1-15.6-.1l-75.7-76.3a11 11 0 0 1 .1-15.6l26-25.8a11 11 0 0 1 15.6.1l42.1 42.5 97.2-96.4a11 11 0 0 1 15.6.1l25.8 26a11 11 0 0 1-.1 15.5z">
                                    </path>
                                    <path class="fa-primary" fill="currentColor"
                                          d="M240 0H98.6a47.87 47.87 0 0 0-45.5 32.8L2.5 184.6c-.8 2.4-.8 4.9-1.2 7.4H240zm208 80a221.93 221.93 0 0 1 27.2 1.7l-16.3-48.8A47.83 47.83 0 0 0 413.4 0H272v157.4C315.9 109.9 378.4 80 448 80zM208 320a238.53 238.53 0 0 1 20.2-96H0v240a48 48 0 0 0 48 48h256.6C246.1 468.2 208 398.6 208 320zm354.2-59.7l-25.8-26a11 11 0 0 0-15.6-.1l-97.2 96.4-42.1-42.5a11 11 0 0 0-15.6-.1l-26 25.8a11 11 0 0 0-.1 15.6l75.7 76.3a11 11 0 0 0 15.6.1l131-130a11 11 0 0 0 .1-15.5z">
                                    </path>
                                </g>
                            </svg><!-- <i class="fal fa-box-check"></i> -->
                        </a>
                    </li>
                    <li>
                        <a href="#" aria-expanded="false">
							<?= Text::_('COM_PROTOSTORE_ORDER_ADDRESS_DETAILS'); ?>
                            <svg width="10px"
                                 class="svg-inline--fa fa-shipping-fast fa-w-20"
                                 aria-hidden="true" focusable="false" data-prefix="fad" data-icon="shipping-fast"
                                 role="img"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
                                <g class="fa-group">
                                    <path class="fa-secondary" fill="currentColor"
                                          d="M248 160H40a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm-24 88v-16a8 8 0 0 0-8-8H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8zm-48 104a80 80 0 1 0 80 80 80 80 0 0 0-80-80zm288 0a80 80 0 1 0 80 80 80 80 0 0 0-80-80zM280 96H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h272a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8z">
                                    </path>
                                    <path class="fa-primary" fill="currentColor"
                                          d="M624 352h-16V243.9a48 48 0 0 0-14.1-33.9L494 110.1A48 48 0 0 0 460.1 96H416V48a48 48 0 0 0-48-48H112a48 48 0 0 0-48 48v48h216a8 8 0 0 1 8 8v16a8 8 0 0 1-8 8H64v32h184a8 8 0 0 1 8 8v16a8 8 0 0 1-8 8H64v32h152a8 8 0 0 1 8 8v16a8 8 0 0 1-8 8H64v112a47.74 47.74 0 0 0 7 25 112 112 0 0 1 215.86 23h66.28a112 112 0 0 1 221.72 0H624a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm-64-96H416V144h44.1l99.9 99.9z">
                                    </path>
                                </g>
                            </svg><!-- <i class="fal fa-shipping-fast"></i> -->
                        </a>
                    </li>
                    <li>
                        <a href="#" aria-expanded="false">
							<?= Text::_('COM_PROTOSTORE_ORDER_EMAILS_AND_LOGS'); ?>
                            <svg width="10px"
                                 class="svg-inline--fa fa-envelope-open-text fa-w-16"
                                 aria-hidden="true" focusable="false" data-prefix="fad"
                                 data-icon="envelope-open-text" role="img"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                <g class="fa-group">
                                    <path class="fa-secondary" fill="currentColor"
                                          d="M64,257.6,227.9,376a47.72,47.72,0,0,0,56.2,0L448,257.6V96a32,32,0,0,0-32-32H96A32,32,0,0,0,64,96ZM160,160a16,16,0,0,1,16-16H336a16,16,0,0,1,16,16v16a16,16,0,0,1-16,16H176a16,16,0,0,1-16-16Zm0,80a16,16,0,0,1,16-16H336a16,16,0,0,1,16,16v16a16,16,0,0,1-16,16H176a16,16,0,0,1-16-16Z">
                                    </path>
                                    <path class="fa-primary" fill="currentColor"
                                          d="M352,160a16,16,0,0,0-16-16H176a16,16,0,0,0-16,16v16a16,16,0,0,0,16,16H336a16,16,0,0,0,16-16Zm-16,64H176a16,16,0,0,0-16,16v16a16,16,0,0,0,16,16H336a16,16,0,0,0,16-16V240A16,16,0,0,0,336,224ZM329.4,41.4C312.6,29.2,279.2-.3,256,0c-23.2-.3-56.6,29.2-73.4,41.4L152,64H360ZM64,129c-23.9,17.7-42.7,31.6-45.6,34A48,48,0,0,0,0,200.7v10.7l64,46.2Zm429.6,34c-2.9-2.3-21.7-16.3-45.6-33.9V257.6l64-46.2V200.7A48,48,0,0,0,493.6,163ZM256,417.1a80,80,0,0,1-46.9-15.2L0,250.9V464a48,48,0,0,0,48,48H464a48,48,0,0,0,48-48V250.9l-209.1,151A80,80,0,0,1,256,417.1Z">
                                    </path>
                                </g>
                            </svg><!-- <i class="fal fa-envelope-open-text"></i> -->
                        </a>
                    </li>
                </ul>
                <ul class="uk-switcher uk-margin">
                    <li>
						<?= LayoutHelper::render('order.details', array(
							'item'      => $item,
							'cardStyle' => 'default',
							'cardTitle' => 'COM_PROTOSTORE_ORDER_DETAILS',
							'cardId'    => 'details',
							'fields'    => array()
						)); ?>

						<?= LayoutHelper::render('order.products', array(
							'item'      => $item,
							'cardStyle' => 'default',
							'cardTitle' => 'COM_PROTOSTORE_ORDER_PRODUCTS_ORDERED',
							'cardId'    => 'products',
							'fields'    => array()
						)); ?>
						<?= LayoutHelper::render('order.customer', array(
							'item'      => $item,
							'cardStyle' => 'default',
							'cardTitle' => 'COM_PROTOSTORE_ORDER_CUSTOMER_DETAILS',
							'cardId'    => 'products',
							'fields'    => array()
						)); ?>
                    </li>
                    <li>
                        <div class="uk-grid" uk-grid="">
                            <div class="uk-width-1-2">
								<?= LayoutHelper::render('order.shipping', array(
									'item'      => $item,
									'cardStyle' => 'default',
									'cardTitle' => 'COM_PROTOSTORE_ORDER_SHIPPING_DETAILS',
									'cardId'    => 'products',
									'fields'    => array()
								)); ?>
                            </div>
                            <div class="uk-width-1-2">
								<?= LayoutHelper::render('order.billing', array(
									'item'      => $item,
									'cardStyle' => 'default',
									'cardTitle' => 'COM_PROTOSTORE_ORDER_BILLING_DETAILS',
									'cardId'    => 'products',
									'fields'    => array()
								)); ?>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="uk-grid" uk-grid="">
                            <div class="uk-width-1-2">
								<?= LayoutHelper::render('order.emailactions', array(
									'item'      => $item,
									'cardStyle' => 'default',
									'cardTitle' => 'COM_PROTOSTORE_ORDER_EMAIL_ACTIONS',
									'cardId'    => 'products',
									'fields'    => array()
								)); ?>
								<?= LayoutHelper::render('order.log', array(
									'item'      => $item,
									'cardStyle' => 'default',
									'cardTitle' => 'COM_PROTOSTORE_ORDER_LOG',
									'cardId'    => 'products',
									'fields'    => array()
								)); ?>
                            </div>
                            <div class="uk-width-1-2">
								<?= LayoutHelper::render('order.emaillog', array(
									'item'      => $item,
									'cardStyle' => 'default',
									'cardTitle' => 'COM_PROTOSTORE_ORDER_EMAIL_LOG',
									'cardId'    => 'products',
									'fields'    => array()
								)); ?>
								<?= LayoutHelper::render('order.internalnotes', array(
									'item'      => $item,
									'cardStyle' => 'default',
									'cardTitle' => 'COM_PROTOSTORE_ORDER_INTERNAL_NOTES',
									'cardId'    => 'products',
									'fields'    => array()
								)); ?>
                            </div>
                        </div>
                    </li>
                </ul>


            </div>


            <div class="uk-width-1-3">
                <div class="uk-card uk-card-default uk-margin-medium-top">

                    <div class="uk-card-header">
                        <h4> Controls</h4>
                    </div>
                    <div class="uk-card-body">
                        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>

                            <li>
                                <a class="uk-text-emphasis" href="index.php?option=com_protostore&view=discount">
                                    <span class="uk-margin-small-right" uk-icon="icon: plus-circle"></span>
                                    Mark as Paid
                                </a>
                            </li>

                            <li class="uk-nav-divider"></li>
                            <li>
                                <a>
                                    <span class="uk-margin-small-right" uk-icon="icon: trash"></span>
                                    Change Status
                                </a>
                            </li>
                            <li>
                                <a>
                                    <span class="uk-margin-small-right" uk-icon="icon: check"></span>
                                    Toggle Published
                                </a>

                            </li>
                            <li>
                                <a>
                                    <span class="uk-margin-small-right" uk-icon="icon: check"></span>
                                    Print Invoice
                                </a>

                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<script>
    const p2s_order_form = {
        data() {
            return {
                base_url: '',
                order: [],
                andClose: false

            }

        },
        mounted() {

        },
        computed: {},
        async beforeMount() {

            const base_url = document.getElementById('base_url');
            this.base_url = base_url.innerText;
            base_url.remove();

            const currency = document.getElementById('currency');
            if (currency) {
                this.currency = JSON.parse(currency.innerText);
            }
            currency.remove();

            const locale = document.getElementById('locale');
            if (locale) {
                this.locale = locale.innerText;
            }
            locale.remove();
            const order = document.getElementById('p2s_order');
            try {
                this.order = JSON.parse(order.innerText);
                order.remove();
            } catch (err) {
            }


        },
        methods: {

            async saveItem() {

                const params = {
                    itemid: this.form.jform_id,

                };

                const URLparams = this.serialize(params);

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.save&format=raw&" + URLparams);

                const response = await request.json();


                if (response.success) {

                    UIkit.notification({
                        message: 'Saved!',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 5000
                    });

                    if (this.andClose) {
                        // if 'andClose' is true, redirect back to the discounts list page
                        window.location.href = this.base_url + 'index.php?option=com_protostore&view=discounts';
                    } else {

                    }

                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }


            },
            copyOrderToClipboard() {

                let str = '';
                for (const [key, value] of Object.entries(this.order)) {
                    str += ` ${key}: ${value} `;
                }
                this.copyToClipboard(str);
            },
            copyToClipboard(str) {


                str = JSON.stringify(str);

                const el = document.createElement('textarea');
                el.value = str;
                el.setAttribute('readonly', '');
                el.style.position = 'absolute';
                el.style.left = '-9999px';
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            },
            togglePaid() {
                console.log(this.order);
            },
            setData() {
                const keys = Object.keys(this.form);
                keys.forEach((jfrom) => {
                    let theInput = document.getElementById(jfrom + '_data');
                    if (theInput) {
                        this.form[jfrom] = theInput.innerText;
                        theInput.remove();
                    }

                });
            },
            serialize(obj) {
                var str = [];
                for (var p in obj)
                    if (obj.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    }
                return str.join("&");
            }
        },
        components: {
            'p-inputswitch': primevue.inputswitch
        }
    }
    Vue.createApp(p2s_order_form).mount('#p2s_order_form');


</script>

