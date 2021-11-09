<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

$id = uniqid('yps_checkoutnotes');

$el = $this->el('div', [

    'class' => [
        'uk-panel {@!style}',
        'uk-card uk-card-body uk-{style}',
    ]
]);

?>

<?= $el($props) ?>
<div id="<?= $id; ?>">
<textarea class="uk-textarea" rows="<?= $props['rows']; ?>" placeholder="<?= $props['placeholder_text']; ?>"
          v-model="input"
          id="yps_checkout_note"><?= $props['note']; ?></textarea>
</div>

<?= $el->end(); ?>


<script>
    const <?= $id; ?> = {
        data() {
            return {
                message: '<?= $props['note']; ?>'
            }
        },
        methods: {
            async doneTyping() {

                const params = {
                    'note': this.message,
                };



                //const request = await fetch('<?//= $props['baseUrl']; ?>//index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=checkoutnote.save&format=raw&' + URLparams, {method: 'post'});
                //
                //const params = {
                //    'paymentType':  this.paymentType
                //};

                const request = await fetch('<?= $props['baseUrl']; ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=checkoutnote.save&format=raw', {
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
        },
        computed: {
            input: {
                get() {
                    return this.message
                },
                set(val) {
                    if (this.timeout) clearTimeout(this.timeout)
                    this.timeout = setTimeout(() => {
                        this.message = val
                        this.doneTyping();
                    }, 300);
                }
            }
        }
    }


    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')


</script>
