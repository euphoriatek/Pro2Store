<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

/** @var $checkoutLink */
/** @var $count */
/** @var $total */
/** @var $cartItems */
/** @var $locale */
/** @var $currency */

/** @var $params */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$id = uniqid('yps_cart_module');


?>
<div id="<?= $id; ?>">

    <div v-cloak class=" uk-visible@m uk-inline boundary-align <?= $params->get('text_colour'); ?>">

        <div>
            <span id="yps_cart_spinner" class="uk-hidden" uk-spinner="ratio: .5"></span>
            <a href="<?= $checkoutLink; ?>">
                <span id="yps_cart_icon" uk-icon="icon: cart"></span>
                <span class="uk-badge" id="cartcount">{{count}}</span>
            </a>
        </div>
		<?php if ($params->get('show_drop', '1')): ?>
            <div uk-drop="pos: bottom-justify; boundary: .uk-container; boundary-align: true; animation: uk-animation-slide-top-small; duration: 200;mode: hover"
                 class=" uk-width-large">
                <div id="yps-iconcart-drop"
                     class="uk-card <?= $params->get('drop_card_style'); ?> <?= $params->get('text_colour'); ?>"
                     style="min-width: <?= $params->get('min_width', '200'); ?>px">
                    <div class="uk-card-body">
                        <table class="uk-table uk-table-striped uk-table-small">
                            <thead>
                            <tr>
                                <th></th>
                                <th>
                                    <span style="color: <?= ($params->get('text_colour') == 'uk-light' ? '#ffffff' : '#000000'); ?>">Product</span>
                                </th>
                                <th class="uk-width-small uk-text-nowrap uk-text-right"><span
                                            style="color: <?= ($params->get('text_colour') == 'uk-light' ? '#ffffff' : '#000000'); ?>">Total</span>
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="yps-iconcart-tablebody">
                            <tr v-for="item in cartItems">
                                <td class="uk-table-shrink">
                                    <img class="uk-preserve-width" alt="" width="80"
                                         v-bind:src="baseUrl + item.images?.image_fulltext">
                                </td>
                                <td class="uk-table-expand">
                                    <h6>
                                        <span style="color: <?= ($params->get('text_colour') == 'uk-light' ? '#ffffff' : '#000000'); ?>">{{item.joomla_item_title}} x {{item.count}}</span>
                                    </h6>
                                    <ul class="uk-list uk-list-collapse">
                                        <li v-for="option in item.selected_options" class="">
                                            {{option.optiontypename}}:
                                            {{option.optionname}}
                                        </li>
                                    </ul>
                                </td>

                                <td class="uk-width-small uk-text-nowrap uk-text-right">{{
                                    itemPrice(item.bought_at_price,
                                    item.count) }}
                                </td>
                                <td class="uk-table-shrink uk-text-nowrap uk-text-right"><span
                                            @click="remove(item.cart_id, item.cart_itemid)" uk-icon="icon: trash"
                                            style="width: 20px; cursor: pointer"></span>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                    <div class="uk-card-footer">
                        <a class="uk-button uk-button-primary" href="<?= $checkoutLink; ?>">Checkout</a>
                    </div>

                </div>
            </div>
		<?php endif; ?>
    </div>

</div>
<script>
    const <?= $id; ?> = {
        data() {
            return {
                count: <?= $count; ?>,
                total: '<?= $total; ?>',
                cartItems: <?= json_encode($cartItems); ?>,
                locale: '<?= $locale ?>',
                iso: '<?= $currency->iso ?>',
                loading: false,
                COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART: '<?= addslashes(Text::_('COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART')); ?>'
            }

        },
        mounted() {
            emitter.on("yps_cart_update", this.fetchCartItems)
        },
        methods: {
            async fetchCartItems() {
// TODO - FIX THIS SHIT!
                this.loading = true;

                const requestCartItems = await fetch("<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=updatecart&format=raw", {
                    method: 'post',
                });

                const responseCartItems = await requestCartItems.json();

                if (responseCartItems.success) {
                    this.cartItems = responseCartItems.data.cartItems;
                    this.count = responseCartItems.data.cartCount;
                    this.total = responseCartItems.data.total;
                    this.loading = false;
                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

            },
            changeCount() {

            },
            remove(cartid, cartitemid) {

                UIkit.modal.confirm(this.COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART).then(function () {
                    fetch("<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=removeallfromcart&format=raw&cartid=" + cartid + '&cartitemid=' + cartitemid, {
                        method: 'post'
                    }).then(function (res) {
                        return res.json();
                    }).then(function (response) {
                        if (response.success) {
                            emitter.emit('yps_cart_update');
                        }
                    });
                });


            },
            itemPrice(bought_at_price, count) {

                const num = (bought_at_price * count) / 100;

                return num.toLocaleString(this.locale, {
                    style: 'currency', currency: this.iso
                });
            }

        }
    }
    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>');

</script>


