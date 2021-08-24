<?php
/**
 * @package     Pro2Store Cart
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Elm House Creative. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$id = uniqid('yps_cart');
?>

<script id="yps_cart_module-baseUrl" type="application/json"><?= Uri::base(); ?></script>
<script id="yps_cart_module-data-count" type="application/json"><?= $count; ?></script>
<script id="yps_cart_module-data-cartitems" type="application/json"><?= json_encode($cartItems); ?></script>
<script id="yps_cart_module-data-locale" type="application/json"><?= $locale ?></script>
<script id="yps_cart_module-data-total" type="application/json"><?= $total; ?></script>
<script id="yps_cart_module-data-iso" type="application/json"><?= $currencyHelper->currency->iso ?></script>
<script id="yps-cart_module-items-trans-remove-all-items"
        type="application/json"><?= Text::_('COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART'); ?></script>
<div v-cloak id="<?= $id; ?>">
    <a class="uk-button
    uk-button-<?= $params->get('button_type'); ?>
    uk-button-<?= $params->get('button_size'); ?>" href="<?= $checkoutLink; ?>">

        <span><?= Text::_('COM_PROTOSTORE_ORDER_TOTAL'); ?> <span class="yps_cart_total">{{ total }}</span>

    <span v-show="!loading" uk-icon="icon: cart" class="uk-icon uk-margin-auto-left"></span>
    <span v-if="loading" uk-spinner style="width: 20px;"></span>

    </a>

</div>


<script>
    const yps_cart_module<?= $id; ?> = {
        data() {
            return {
                baseUrl: '',
                count: 0,
                total: 0,
                cartItems: [],
                locale: '',
                iso: '',
                loading: false,
                COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART: ''
            }

        },
        async beforeMount() {

            // set the data from the inline scripts
            const baseUrl = document.getElementById('yps_cart_module-baseUrl');
            this.baseUrl = baseUrl.innerText;
            baseUrl.remove();

            const count = document.getElementById('yps_cart_module-data-count');
            this.count = count.innerText;
            count.remove();

            const cartItems = document.getElementById('yps_cart_module-data-cartitems');
            this.cartItems = JSON.parse(cartItems.innerText);
            cartItems.remove();

            const total = document.getElementById('yps_cart_module-data-total');
            this.total = total.innerText;
            total.remove();

            const locale = document.getElementById('yps_cart_module-data-locale');
            this.locale = locale.innerText;
            locale.remove();

            const iso = document.getElementById('yps_cart_module-data-iso');
            this.iso = iso.innerText;
            iso.remove();

            const removeAll = document.getElementById('yps-cart_module-items-trans-remove-all-items');
            this.COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART = removeAll.innerText;
            removeAll.remove();

        },
        mounted() {
            emitter.on("yps_cart_update", this.fetchCartItems)

        },
        methods: {
            async fetchCartItems() {

                this.loading = true;

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type==cart.update&format=raw", {
                    method: 'post',
                });

                const response = await request.json();

                if (response.success) {
                    this.cartItems = response.data.cartItems;
                    this.count = response.data.cartCount;
                    this.total = response.data.total;
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
                    fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=removeallfromcart&format=raw&cartid=" + cartid + '&cartitemid=' + cartitemid, {
                        method: 'post'
                    }).then(function (res) {
                        return res.json();
                    }).then(function (response) {
                        ;
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
    Vue.createApp(yps_cart_module<?= $id; ?>).mount('#<?= $id; ?>')

</script>


