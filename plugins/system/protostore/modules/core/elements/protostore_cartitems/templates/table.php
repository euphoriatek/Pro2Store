<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

use Joomla\CMS\Language\Text;
use Protostore\Config\ConfigFactory;
use Protostore\Language\LanguageFactory;


/** @var $props array */
/** @var $attrs array */

LanguageFactory::load();

$id = uniqid('yps_cartitems_table');


$el = $this->el('div', [

	'class' => [
		'uk-panel {@!style}',
		'uk-card uk-card-body uk-{style}',
		'tm-child-list [tm-child-list-{list_style}] [uk-link-{link_style}] {@is_list}',
	],

]);

$componentParams = ConfigFactory::get();

?>


<?= $el($props, $attrs) ?>

<?php if (empty($props['cartItems'])) : ?>


	<?php $title = $this->el($props['title_element'], [

		'class' => [
			'uk-{title_style}',
			'uk-heading-{title_decoration}',
			'uk-font-{title_font_family}',
			'uk-text-{title_color} {@!title_color: background}',
			'uk-margin-remove {position: absolute}',
		],

	]);
	?>
	<?= $title($props, $attrs) ?>
	<?= $props['empty_text']; ?>
	<?= $title->end(); ?>
<?php else : ?>


    <div class="uk-overflow-auto" id="<?= $id; ?>">
        <table v-cloak class="uk-table uk-table-middle">
            <tbody>

            <tr v-for="item in cartItems">
                <td class="uk-table-shrink">
                    <img class="uk-preserve-width" alt="" :width="'80'" style="width: 80px"
                         :src="item.product.images.image_intro">
                </td>
                <td class="uk-table-expand">
                    <h4>{{item.product.joomlaItem.title}}</h4>
                    <ul class="uk-list">
                        <li v-if="item.selected_variant">{{item.selected_variant.labels_csv}}</li>
                        <li v-for="selected_option in item.selected_options">{{selected_option.option_name}}:
                            {{selected_option.modifier_value_translated}}
                        </li>
                    </ul>

                </td>
                <td class="uk-table-shrink">
                    <input @input="changeCountDelay(item)" v-model="item.amount" class="uk-input" style="width: 70px;"
                           min="0"
                           :max="(parseInt(item.product.manage_stock) === 1 ? item.product.stock : '')"
                           type="number">
                </td>
				<?php if ($props['include_tax']) : ?>
                    <td class="uk-width-small uk-text-nowrap uk-text-right">
                        {{item.total_bought_at_price_with_tax_formatted}}
                    </td>
				<?php else : ?>
                    <td class="uk-width-small uk-text-nowrap uk-text-right">{{item.total_bought_at_price_formatted}}
                    </td>
				<?php endif; ?>


                <td class="uk-table-shrink uk-text-nowrap uk-text-right"><span
                            @click="remove(item.id)" uk-icon="icon: trash"
                            style="width: 20px; cursor: pointer"></span>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
<?php endif; ?>
<?= $el->end(); ?>


<script>

    const <?= $id; ?> = {
        data() {
            return {
                cartItems: <?= json_encode($props['cartItems']); ?>,
                COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART: '<?= Text::_('COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART'); ?>'
            }

        },

        created() {
            emitter.on("yps_cart_update", this.fetchCartItems)
            emitter.on("yps_product_update", this.fetchCartItems)
        },
        methods: {
            async changeCount(item) {

                // make sure we can't go over the stock level
                if (parseInt(item.product.manage_stock) === 1) {
                    if (parseInt(item.amount) > parseInt(item.product.stock)) {
                        item.amount = item.product.stock;
                    }
                }

                const params = {
                    'cartitemid': item.id,
                    'newcount': item.amount,
                    'itemId': item.joomla_item_id,
                };

                const URLparams = this.serialize(params);

                const request = await fetch('<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.changecount&format=raw&' + URLparams, {
                    method: 'post',
                });

                const response = await request.json();

                if (response.success) {
                    emitter.emit("yps_cart_update");
                } else {
                    UIkit.notification({
                        message: 'There was an error updating the amount.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

            },
            async fetchCartItems() {

                const request = await fetch("<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.update&format=raw", {
                    method: 'post',
                });

                const response = await request.json();

                if (response.success) {
                    this.cartItems = response.data.cartItems;
                } else {
                    UIkit.notification({
                        message: 'There was an error fetching the items.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

            },
            async remove(cartitemid) {

                await UIkit.modal.confirm(this.COM_PROTOSTORE_ELM_CARTITEMS_ALERT_REMOVE_ALL_FROM_CART);

                const params = {
                    'cartitemid': cartitemid
                };

                const URLparams = this.serialize(params);

                const request = await fetch('<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.removeall&format=raw&' + URLparams, {
                    method: 'post'
                });

                const response = await request.json();
                if (response.success) {
                    emitter.emit("yps_cart_update");
                } else {
                    UIkit.notification({
                        message: 'There was an error removing the items.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }


            },
            changeCountDelay(item) {
                if (this.timeout) {
                    clearTimeout(this.timeout)
                }
                this.timeout = setTimeout(() => {
                    this.changeCount(item);
                }, 750);
            },
            serialize(obj) {
                var str = [];
                for (var p in obj)
                    if (obj.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    }
                return str.join("&");
            }

        }
    }
    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')


</script>

