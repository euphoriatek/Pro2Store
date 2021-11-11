<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

$id = uniqid('yps_quantity');

if ($props['instock']) : ?>


    <?php

// Create div tag
    $el = $this->el('div', [

        'class' => [
            'uk-panel {@!style}',
            'uk-card uk-card-body uk-{style}',
        ]
    ]);

    ?>
    <script id="yps-quantity-data-max" type="application/json"><?= $props['max']; ?></script>
    <?= $el($props) ?>

    <div id="<?= $id; ?>">

        <?php if ($props['button_position'] == 'right') : ?>
            <div class="uk-grid uk-grid-collapse uk-margin" uk-grid="">
                <div class="uk-width-1-2">
                    <input class="uk-input" type="number" min="1" v-model="amount" max="<?= $props['max']; ?>"
                           @input="changeByText">
                </div>
                <div class="uk-width-1-2">
                    <div class="uk-button-group uk-width-1-1 uk-margin-small-left">
                        <button @click="changeAmount(1)"
                                class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button' . $props['button_size'] : ''); ?>">
                            +
                        </button>
                        <button @click="changeAmount(-1)"
                                class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button' . $props['button_size'] : ''); ?>">
                            -
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($props['button_position'] == 'left') : ?>
            <div class="uk-grid uk-grid-small uk-margin" uk-grid="">
                <div class="uk-width-auto">
                    <div class="uk-button-group uk-width-1-1">
                        <button @click="changeAmount(1)"
                                class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button-' . $props['button_size'] : ''); ?>">
                            +
                        </button>
                        <button @click="changeAmount(-1)"
                                class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button-' . $props['button_size'] : ''); ?>">
                            -
                        </button>
                    </div>
                </div>
                <div class="uk-width-expand">
                    <input class="uk-input uk-form-<?= $props['button_size']; ?>" id="yps_amount" type="number" min="1"
                           @input="changeByText"
                           v-model="amount" max="<?= $props['max']; ?>">
                </div>

            </div>
        <?php endif; ?>

        <?php if ($props['button_position'] == 'split') : ?>
            <div class="uk-grid uk-grid-small uk-margin" uk-grid="">
                <div class="uk-width-auto">
                    <button @click="changeAmount(1)"
                            class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button-' . $props['button_size'] : ''); ?>">
                        +
                    </button>
                </div>
                <div class="uk-width-expand">
                    <input class="uk-input uk-form-<?= $props['button_size']; ?>" id="yps_amount" type="number" min="1"
                           @input="changeByText"
                           v-model="amount" max="<?= $props['max']; ?>">
                </div>
                <div class="uk-width-auto">
                    <button @click="changeAmount(-1)"
                            class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button-' . $props['button_size'] : ''); ?>">
                        -
                    </button>
                </div>

            </div>
        <?php endif; ?>

        <?php if ($props['button_position'] == 'split-inverse') : ?>
            <div class="uk-grid uk-grid-small uk-margin" uk-grid="">
                <div class="uk-width-auto">
                    <button @click="changeAmount(-1)"
                           class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button-' . $props['button_size'] : ''); ?>">
                        -
                    </button>
                </div>
                <div class="uk-width-expand">
                    <input class="uk-input uk-form-<?= $props['button_size']; ?>" id="yps_amount" type="number" min="1"
                           @input="changeByText"
                           v-model="amount" max="<?= $props['max']; ?>">
                </div>
                <div class="uk-width-auto">

                    <button @click="changeAmount(1)"
                            class="uk-button uk-button-<?= $props['button_style']; ?> <?= ($props['button_size'] ? 'uk-button-' . $props['button_size'] : ''); ?>">
                        +
                    </button>
                </div>

            </div>
        <?php endif; ?>
    </div>
    <?= $el->end(); ?>


<?php endif; ?>

<script>
    const <?= $id; ?> = {
        data() {
            return {
                amount: 1,
                max: 1
            }
        },
        async beforeMount() {

            const max = document.getElementById('yps-quantity-data-max');
            try {
                this.max = max.innerText;
            } catch (err) {
                throw new Error('Data is missing...')
            }

            if (!this.max) {
                throw new Error('Data is missing...')
            }
        },
        mounted() {
            const max = document.getElementById('yps-quantity-data-max');
            try {
                this.max = max.innerText;
            } catch (err) {
                throw new Error('Data is missing...')
            }

            if (!this.max) {
                throw new Error('Data is missing...')
            }
        },
        methods: {
            changeAmount: function (by) {

                if (by == 1 && (this.amount == this.max)) {
                    return;
                }

                if (this.amount == 1 && by == -1) {
                    return;
                }

                if (by == 1) {
                    this.amount = Number(this.amount) + 1
                } else if (by == -1) {
                    this.amount = Number(this.amount) - 1
                }

                if (this.amount > this.max) {
                    this.amount = this.max;
                }

                if (this.amount < 1) {
                    this.amount = 1;
                }


                emitter.emit('yps_amountChange', this.amount)
                emitter.emit('yps_product_update', this.amount)

            },
            changeByText() {

                if (this.amount > this.max) {
                    this.amount = this.max;
                }

                if (this.amount < 1) {
                    this.amount = 1;
                }

                emitter.emit('yps_amountChange', this.amount)
                emitter.emit('yps_product_update', this.amount)
            }
        }
    }

    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')

</script>
