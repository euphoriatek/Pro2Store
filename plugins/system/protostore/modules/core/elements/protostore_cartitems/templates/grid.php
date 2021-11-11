<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

use Joomla\CMS\Language\Text;
use Protostore\Language\LanguageFactory;

/** @var $props array */
/** @var $attrs array */


LanguageFactory::load();

$id = uniqid('yps_cartitems_grid');

$el = $this->el('div', [

	'class' => [
		'uk-panel {@!style}',
		'uk-card uk-card-body uk-{style}'
	],

]);


?>


<?= $el($props, $attrs) ?>

<div id="<?= $id; ?>">

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


        <div v-for="item in cartItems" class="uk-animation-fade" v-cloak
             style="overflow:hidden;transition:height 0.3s ease-out;height:auto;">

            <div class="uk-position-relative uk-float-right">
                    <span uk-tooltip="title: <?= Text::_('COM_PROTOSTORE_ELM_CARTITEMS_REMOVE_FROM_CART'); ?>"
                          @click="remove(item.id)"
                          style="cursor: pointer">
                        <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="times-circle"
                             width="20px"
                             class="svg-inline--fa fa-times-circle fa-w-16" role="img"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                  d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 464c-118.7 0-216-96.1-216-216 0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216 0 118.7-96.1 216-216 216zm94.8-285.3L281.5 256l69.3 69.3c4.7 4.7 4.7 12.3 0 17l-8.5 8.5c-4.7 4.7-12.3 4.7-17 0L256 281.5l-69.3 69.3c-4.7 4.7-12.3 4.7-17 0l-8.5-8.5c-4.7-4.7-4.7-12.3 0-17l69.3-69.3-69.3-69.3c-4.7-4.7-4.7-12.3 0-17l8.5-8.5c4.7-4.7 12.3-4.7 17 0l69.3 69.3 69.3-69.3c4.7-4.7 12.3-4.7 17 0l8.5 8.5c4.6 4.7 4.6 12.3 0 17z"></path></svg>
                    </span>
            </div>

            <div class="uk-grid-margin uk-grid" uk-grid="">
                <div class="uk-width-auto">
                    <div class="uk-margin">
                        <img class="uk-preserve-width" alt="" width="80"
                             :src="item.product.images.image_intro">
                    </div>
                </div>

                <div class="uk-width-auto">
                    <h3 class="">
                        {{item.product.joomlaItem.title}} x {{item.amount}}</h3>
					<?php if ($props['include_tax']) : ?>
                        <div class="uk-text-meta uk-margin">
                            {{item.total_bought_at_price_with_tax_formatted}}
                        </div>
					<?php else : ?>
                        <div class="uk-text-meta uk-margin">
                            {{item.total_bought_at_price_formatted}}
                        </div>
					<?php endif; ?>
                    <ul class="uk-list">
                        <li v-if="item.selected_variant">{{item.selected_variant.labels_csv}}</li>
                        <li v-for="selected_option in item.selected_options">{{selected_option.option_name}}:
                            {{selected_option.modifier_value_translated}}
                        </li>
                    </ul>
                </div>

            </div>
			<?php if (count($props['cartItems']) > 1) : ?>
                <hr class="uk-divider-icon">
			<?php endif; ?>
        </div>


	<?php endif; ?>
</div>
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

            async fetchCartItems() {

                const requestCartItems = await fetch("<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.update&format=raw", {
                    method: 'post',
                });

                const responseCartItems = await requestCartItems.json();

                if (responseCartItems.success) {
                    this.cartItems = responseCartItems.data.cartItems;
                } else {
                    UIkit.notification({
                        message: 'There was an error.',
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

        }
    }
    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')

</script>
