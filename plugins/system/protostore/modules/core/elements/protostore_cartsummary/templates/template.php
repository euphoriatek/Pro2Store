<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

/** @var $attrs array */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$componentConfig = \Protostore\Config\ConfigFactory::get();

$id = uniqid('yps_cartsummary');

$language = Factory::getLanguage();
$language->load('com_protostore', JPATH_ADMINISTRATOR);

$el = $this->el('div', [

	'class' => [
		'uk-panel {@!style}',
		'uk-card uk-card-body uk-{style}',
	]

]);


?>

<script id="yps-cart-summary-cart" type="application/json"><?= json_encode($props['cart']); ?></script>

<?= $el($props, $attrs) ?>
<div id="<?= $id; ?>">
    <ul class="uk-list uk-width-1-1">
        <li class="el-item">
            <div class="uk-child-width-auto uk-grid-small uk-flex-bottom uk-grid" uk-grid="">
                <div class="uk-width-expand uk-first-column">
                <span class="el-title uk-display-block uk-leader" uk-leader="">
                    <span class="uk-leader-fill"><?= Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_SUBTOTAL'); ?></span></span>
                </div>
                <div>

                    <div v-cloak class=" uk-h5 uk-margin-remove yps-cartsummary-subtotal">{{cart.subtotal}}</div>

                </div>
            </div>


        </li>
		<?php if ($props['show_shipping']) : ?>
            <li class="el-item">

                <div class="uk-child-width-auto uk-grid-small uk-flex-bottom uk-grid" uk-grid="">
                    <div class="uk-width-expand uk-first-column">
                <span class="el-title uk-display-block uk-leader" uk-leader=""><span
                            class="uk-leader-fill"><?= Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_SHIPPING'); ?></span></span>
                    </div>
                    <div>
                        <div v-cloak class=" uk-h5 uk-margin-remove yps-cartsummary-totalshipping">
                            {{cart.totalShippingFormatted}}
                        </div>
                    </div>
                </div>
            </li>
		<?php endif; ?>

        <li id="yps-cartsummary-totaldiscountList"
            class="el-item" v-show="showDiscount">
            <div class="uk-child-width-auto uk-grid-small uk-flex-bottom uk-grid" uk-grid="">
                <div class="uk-width-expand uk-first-column">
                <span class="el-title uk-display-block uk-leader" uk-leader=""><span
                            class="uk-leader-fill"><?= Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_DISCOUNTS'); ?></span></span>
                </div>
                <div>
                    <div v-cloak class=" uk-h5 uk-margin-remove yps-cartsummary-totaldiscount">{{cart.totalDiscount}}
                    </div>
                </div>
            </div>

        </li>


        <li id="yps-cartsummary-taxList" class="el-item" v-show="showTax">
            <div class="uk-child-width-auto uk-grid-small uk-flex-bottom uk-grid" uk-grid="">
                <div class="uk-width-expand uk-first-column">
                <span class="el-title uk-display-block uk-leader" uk-leader=""><span
                            class="uk-leader-fill"><?= Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_TAX'); ?></span></span>
                </div>
                <div>
					<?php if ($componentConfig->get('add_default_country_tax_to_price', '1') == "1") : ?>
                        <div v-cloak class=" uk-h5 uk-margin-remove yps-cartsummary-totaltax">{{cart.default_tax}}</div>
					<?php else: ?>
                        <div v-cloak class=" uk-h5 uk-margin-remove yps-cartsummary-totaltax">{{cart.tax}}</div>
					<?php endif; ?>
                </div>
            </div>

        </li>


        <li class=" el-item  ">

            <div class="uk-child-width-auto uk-grid-small uk-flex-bottom uk-grid" uk-grid="">
                <div class="uk-width-expand uk-first-column">
                <span class="el-title uk-display-block uk-leader" uk-leader=""><span
                            class="uk-leader-fill"><?= Text::_('COM_PROTOSTORE_ELM_CARTSUMMARY_TOTAL'); ?></span></span>
                </div>
                <div>

					<?php if ($componentConfig->get('add_default_country_tax_to_price', '1') == "1") : ?>
                        <div v-cloak class=" uk-h5 uk-margin-remove yps-cartsummary-grandtotal">
                            {{cart.totalWithDefaultTax}}
                        </div>
					<?php else: ?>
                        <div v-cloak class=" uk-h5 uk-margin-remove yps-cartsummary-grandtotal">{{cart.totalWithTax}}
                        </div>
					<?php endif; ?>
                </div>
            </div>


        </li>
    </ul>
</div>
</div>

<script>
    const <?= $id; ?> = {
        data() {
            return {
                cart: {
                    subtotal: 0,
                    totalShipping: 0,
                    totalDiscount: 0,
                    tax: 0,
                    total: 0
                },
                hide_zero_discount: <?= $props['hide_zero_discount'] ? 'true' : 'false'; ?>,
                hide_zero_tax: <?= $props['hide_zero_tax'] ? 'true' : 'false'; ?>
            }
        },
        created() {
            emitter.on("yps_cart_update", this.yps_recalculateCartSummary)
        },
        async beforeMount() {
            // set the data from the inline scripts
            const cart = document.getElementById('yps-cart-summary-cart');
            this.cart = JSON.parse(cart.innerText);
        },
        methods: {

            async yps_recalculateCartSummary() {

                const request = await fetch("<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.update&format=raw", {
                    method: 'post',
                });

                const response = await request.json();

                if (response.success) {
                    this.cart = response.data;
                } else {
                    UIkit.notification({
                        message: 'There was an error running "Update Cart" from summary element.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

            }

        },
        computed: {
            showDiscount: function () {
                const totalInt = this.cart.totalDiscount.replace(/\D/g, '');

                if (this.hide_zero_discount == 0) {
                    return true;
                }

                if (this.hide_zero_discount == 1 && totalInt < 1) {
                    return false;
                } else {
                    return true;
                }
            },
            showTax: function () {
                const totalInt = this.cart.tax.replace(/\D/g, '');

                if (this.hide_zero_tax == 0) {
                    return true;
                }

                if (this.hide_zero_tax == 1 && totalInt < 1) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')

</script>
