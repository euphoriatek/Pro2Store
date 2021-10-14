<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/** @var array $attrs */
/** @var array $props */

$id = uniqid('yps_add_to_cart');


if ($props['instock']) : ?>


	<?php

	$el = $this->el('div', [
		'class' => [
			'uk-panel {@!panel_style}',
			'uk-card uk-{panel_style} [uk-card-{panel_size}]',
			'uk-card-body {@panel_style}'
		]
	]);


	$newButton = '<button @click="yps_addtocart" class="uk-button uk-button-' . $props['button_style'] . ' uk-button-' . $props['button_size'] . ' ' . ($props['fullwidth'] ? "uk-width-1-1" : "") . '">';

	$checkoutbutton = '<button v-show="showCheckoutButton"  @click="yps_gotocheckout" class="uk-animation-fade uk-margin-top uk-button uk-button-' . $props['checkout_button_style'] . ' uk-button-' . $props['checkout_button_size'] . ' ' . ($props['checkout_fullwidth'] ? "uk-width-1-1" : "") . '">';

	?>


	<?= $el($props, $attrs) ?>

    <div id="<?= $id; ?>">

		<?= $newButton ?>

		<?php if ($props['icon']) : ?>

			<?php if ($props['icon_align'] == 'left') : ?>
                <span uk-icon="<?= $props['icon'] ?>"></span>
			<?php endif ?>

            <span class="uk-text-middle"><?= $props['content'] ?></span>

			<?php if ($props['icon_align'] == 'right') : ?>
                <span uk-icon="<?= $props['icon'] ?>"></span>
			<?php endif ?>

		<?php else : ?>
			<?= $props['content'] ?>
		<?php endif ?>

        </button>

        <div v-show="checkout_show">
			<?= $checkoutbutton ?>
			<?php if ($props['checkout_icon']) : ?>

				<?php if ($props['checkout_icon_align'] == 'left') : ?>
                    <span uk-icon="<?= $props['checkout_icon'] ?>"></span>
				<?php endif ?>

                <span class="uk-text-middle"><?= $props['checkout_content'] ?></span>

				<?php if ($props['checkout_icon_align'] == 'right') : ?>
                    <span uk-icon="<?= $props['checkout_icon'] ?>"></span>
				<?php endif ?>

			<?php else : ?>
				<?= $props['checkout_content'] ?>
			<?php endif ?>

            </button>
        </div>
    </div>
    </div>


<?php else : ?>

	<?php

	$el = $this->el($props['oos_element'], [

		'class' => [
			'uk-{oos_style}',
			'uk-heading-{oos_decoration}',
			'uk-font-{oos_font_family}',
			'uk-text-{oos_color} {@!oos_color: background}',
			'uk-margin-remove {position: absolute}',
		],

	]);

	?>

	<?= $el($props, $attrs) ?>
	<?php if ($props['oos_color'] == 'background') : ?>
        <span class="uk-text-background"><?= Text::_($props['oos_message']); ?></span>
	<?php elseif ($props['oos_decoration'] == 'line') : ?>
        <span><?= Text::_($props['oos_message']); ?></span>
	<?php else : ?>
		<?= Text::_($props['oos_message']); ?>
	<?php endif ?>
	<?= $el->end() ?>


<?php endif; ?>


<script>
    const <?= $id; ?> = {
        data() {
            return {
                itemid: <?= $props['item_id']; ?>,
                selectedOptions: [],
                selectedVariant: null,
                amount: 1,
                // layout props
                direct_to_checkout: '<?= $props['direct_to_checkout']; ?>',
                checkoutLink: '<?= $props['checkoutlink']; ?>',
                checkout_show: <?= ($props['checkout_show'] ? 'true' : 'false'); ?>,
                showCheckoutButton: false,
                show_alert: <?= ($props['alert'] ? 'true' : 'false'); ?>,
                alert_message: '<?= $props['alert_message']; ?>',
                alert_style: '<?= $props['alert_style']; ?>',
                alert_position: '<?= $props['alert_position']; ?>',
            }
        },
        created() {
            emitter.on('yps_amountChange', this.setAmount)
            emitter.on('yps_optionsChange', this.setSelectedOptions)
            emitter.on('yps_variantsChange', this.setSelectedVariant)
        },
        mounted() {

        },

        methods: {

            async yps_addtocart() {

                var params = {
                    'j_item_id': this.itemid,
                    'options': this.selectedOptions,
                    'variant': this.selectedVariant,
                    'amount': this.amount
                };

                const request = await fetch("<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.addToCart&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(params)
                });


                const response = await request.json();


                if (response.success) {

                    // first tell all the other Vue instances that we've updated the cart
                    emitter.emit('yps_cart_update');

                    if (this.checkout_show) {
                        this.showCheckoutButton = true;
                    }

                    if (this.show_alert == 'true') {
                        UIkit.notification({
                            message: this.alert_message,
                            status: this.alert_style,
                            pos: this.alert_position,
                            timeout: 5000
                        });
                    }

                    if (this.direct_to_checkout) {
                        window.location.replace(this.checkoutLink);
                    }

                } else {
                    UIkit.notification({
                        message: response.message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

            },
            yps_gotocheckout() {
                window.location.replace(this.checkoutLink);
            },
            setAmount(amount) {
                //set the amount
                this.amount = amount;
            },
            setSelectedOptions(options) {

                //set the selectedOptions
                this.selectedOptions = [];

                options.forEach(option => {
                    if (option.selected) {
                        this.selectedOptions.push(option);
                    }
                });

            },
            setSelectedVariant(setSelectedVariant) {
                //set the selectedOptions
                this.selectedVariant = setSelectedVariant;

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
