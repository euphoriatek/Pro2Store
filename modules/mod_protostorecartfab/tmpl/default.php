<?php
/**
 * @package     Pro2Store Cart
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Elm House Creative. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var $params */
/** @var $checkoutLink string */
/** @var $count int */

use Joomla\CMS\Uri\Uri;

$id = uniqid('yps_cartfab_module');

?>

<div id="<?= $id; ?>">
    <div class="uk-hidden@m">
        <div class="uk-position-fixed uk-position-<?= $params->get('position'); ?>-<?= $params->get('side'); ?> uk-margin-small-<?= $params->get('position') === 'bottom' ? 'bottom' : 'top'; ?> uk-margin-small-<?= $params->get('side') === 'left' ? 'left' : 'right'; ?>" style="z-index: 1000">


            <a href="<?= $checkoutLink; ?>" class="uk-icon-button uk-button-<?= $params->get('button_type'); ?> uk-margin-small-right" style="width: <?= $params->get('size'); ?>px; height: <?= $params->get('size'); ?>px"><span id="yps_cart_fab_icon" uk-icon="icon: cart"></span>{{count}}</a>

        </div>
    </div>
</div>
<script>
    const <?= $id; ?> = {
        data() {
            return {
                count: <?= $count; ?>
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

            }

        }
    }
    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>');

</script>


