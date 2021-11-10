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

    <div v-cloak class=" uk-visible@m p2s_cart_module uk-inline boundary-align <?= $params->get('text_colour'); ?>">

        <div class="uk-text-right">
            <span id="yps_cart_spinner" class="uk-hidden" uk-spinner="ratio: .5"></span>
            <a href="<?= $checkoutLink; ?>">
                <span id="yps_cart_icon" uk-icon="icon: cart"></span>
                <span  class="uk-badge" id="cartcount">{{count}}</span>
            </a>
        </div>
		<?php if ($params->get('show_drop', '1')): ?>
            <div uk-drop="pos: bottom-justify; boundary: .p2s_cart_module; boundary-align: true; animation: uk-animation-slide-top-small; duration: 200;mode: hover" style="width: 550px">
                <div id="yps-iconcart-drop" class="uk-card uk-width-large <?= $params->get('drop_card_style'); ?> <?= $params->get('text_colour'); ?>" style="min-width: <?= $params->get('min_width', '450'); ?>px">
                    <div class="uk-card-body uk-overflow-auto">
                        <table class="uk-table uk-table-striped uk-table-small">
                            <thead>
                            <tr>
                                <th></th>
                                <th><span style="color: <?= ($params->get('text_colour') == 'uk-light' ? '#ffffff' : '#000000'); ?>"><?= Text::_('PROTOSTORE_TABLE_PRODUCT'); ?></span></th>
                                <th class="uk-width-small uk-text-nowrap uk-text-right"> <span style="color: <?= ($params->get('text_colour') == 'uk-light' ? '#ffffff' : '#000000'); ?>"><?= Text::_('PROTOSTORE_TABLE_TOTAL'); ?></span></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="yps-iconcart-tablebody">
                            <tr v-for="item in cartItems">

                                <td class="uk-table-shrink">
                                    <img class="uk-preserve-width" alt="" width="80"
                                         :src="item.product.teaserImagePath">
                                </td>
                                <td class="uk-table-expand">
                                    <h6><span style="color: <?= ($params->get('text_colour') == 'uk-light' ? '#ffffff' : '#000000'); ?>">{{item.product.joomlaItem.title}} x {{item.amount}} </span></h6>
                                    <ul class="uk-list uk-list-collapse">
                                        <li v-for="option in item.product.options" class="">
                                            {{option.option_name}}:
                                            {{option.modifier_value_translated}}
                                        </li>
                                    </ul>

                                </td>

								<?php if($params->get('add_default_country_tax_to_price', '1') == "1") :?>

                                    <td class="uk-width-small uk-text-nowrap uk-text-right">
                                        {{ itemPrice(item.total_bought_at_price_with_tax, item.amount) }}
                                    </td>
								<?php else : ?>
                                    {{item.total_bought_at_price}}
                                    <td class="uk-width-small uk-text-nowrap uk-text-right">
                                        {{ itemPrice(item.total_bought_at_price, item.amount) }}
                                    </td>
								<?php endif; ?>

                                <td class="uk-table-shrink uk-text-nowrap uk-text-right"><span
                                            @click="remove(item.id, item.cart_id)" uk-icon="icon: trash"
                                            style="width: 20px; cursor: pointer"></span>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                    <div class="uk-card-footer">
                        <a class="uk-button uk-button-primary" href="<?= $checkoutLink; ?>"><?= Text::_('PROTOSTORE_CHECKOUT_BUTTON'); ?></a>
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

                this.loading = true;

                const request = await fetch("<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.update&format=raw", {
                    method: 'post',
                });

                const response = await request.json();

                if (response.success) {
                    this.cartItems = response.data.cartItems;
                    this.count = response.data.count;
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
            remove(uid, cart_id) {

                UIkit.modal.confirm(this.COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART).then(function () {
                    fetch("<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.remove&format=raw&uid=" + uid + '&cart_id=' + cart_id, {
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


