<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */



$id = uniqid('yps_tandcs');

$el = $this->el('div', [
    'class' => [
        'uk-panel {@!style}',
        'uk-card uk-card-body uk-{style}',
        'tm-child-list [tm-child-list-{list_style}] [uk-link-{link_style}] {@is_list}',
    ]
]);


?>
<script id="yps_tandcs_checkbox-baseUrl" type="application/json"><?= $props['baseUrl']; ?></script>
<script id="yps_tandcs_checkbox-data" type="application/json"><?= ($props['checked'] ? 'true' : 'false'); ?></script>
<?= $el($props, $attrs) ?>
<div id="<?= $id; ?>">
    <?php if ($props['leftorright']) : ?>
        <input class="uk-checkbox" type="checkbox" v-model="checked" @change="check($event)"
               style="width: <?= $props['width']; ?>px; height: <?= $props['height']; ?>px; border-radius: <?= $props['border_radius']; ?>px; ">
    <?php endif; ?>
    <?php if ($props['linktotandcs']) : ?>

    <a target="_blank" href="<?= $props['termsUrl']; ?> ?>">
        <?php endif; ?>
        <?= $props['tandcs_text']; ?>
        <?php if ($props['linktotandcs']) : ?>
    </a>
<?php endif; ?>
    <?php if (!$props['leftorright']) : ?>
        <input class="uk-checkbox" type="checkbox" v-model="checked" @change="check($event)"
               style="width: <?= $props['width']; ?>px; height: <?= $props['height']; ?>px; border-radius: <?= $props['border_radius']; ?>px; ">
    <?php endif; ?>

</div>
</div>

<script>
    const <?= $id; ?> = {
        data() {
            return {
                baseUrl: '',
                checked: false
            }

        },
        async beforeMount() {
            // set the data from the inline scripts
            const checked = document.getElementById('yps_tandcs_checkbox-data');
            this.checked = checked.innerText;
            checked.remove();

        },
        methods: {
            async check(e) {
                const params = {
                    'state': (this.checked ? 'checked' : 'unchecked'),
                };

                const URLparams = this.serialize(params);

                const request = await fetch('<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=tandcs.toggle&format=raw&' + URLparams, {method: 'post'});

                const response = await request.json();

                if (response.success) {
                    emitter.emit("yps_cart_update")
                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

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


