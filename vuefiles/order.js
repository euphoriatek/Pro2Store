////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const p2s_order_form = {
    data() {
        return {
            base_url: '',
            order: [],
            andClose: false,
            newNoteText: '',
            emailActive: false,
            emailTrackingActive: false

        }

    },
    mounted() {

    },
    computed: {},
    async beforeMount() {

        const base_url = document.getElementById('base_url');
        this.base_url = base_url.innerText;
        base_url.remove();

        const currency = document.getElementById('currency');
        if (currency) {
            this.currency = JSON.parse(currency.innerText);
        }
        currency.remove();

        const locale = document.getElementById('locale');
        if (locale) {
            this.locale = locale.innerText;
        }
        locale.remove();
        const order = document.getElementById('p2s_order');
        try {
            this.order = JSON.parse(order.innerText);
            order.remove();
        } catch (err) {
        }


    },
    methods: {

        async getOrder() {

            const params = {
                orderid: this.order.id,

            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.get&format=raw&" + URLparams);

            const response = await request.json();


            if (response.success) {
                this.order = response.data;

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }

        },

        async saveItem() {

            const params = {
                itemid: this.form.jform_id,
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.save&format=raw&" + URLparams);

            const response = await request.json();


            if (response.success) {

                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }


        },
        copyOrderToClipboard() {

            let str = '';
            for (const [key, value] of Object.entries(this.order)) {
                str += ` ${key}: ${value} `;
            }
            this.copyToClipboard(str);
        },
        copyToClipboard(str) {

            const el = document.createElement('textarea');
            el.value = str;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        },
        togglePaid() {
            console.log(this.order);
        },
        async sendEmail(type) {
            const params = {
                orderid: this.order.id,
                emailtype: type,

            };
            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.sendemail&format=raw&" + URLparams);

            const response = await request.json();

            if (response.success) {

                this.getOrder();

                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });


            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }
        },
        clearNote() {
            this.newNoteText = '';
        },
        async saveNote() {
            const params = {
                orderid: this.order.id,
                text: this.newNoteText,

            };
            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.newnote&format=raw&" + URLparams);

            const response = await request.json();


            if (response.success) {

                this.getOrder();
                await UIkit.modal('#addnotemodal').hide();
                this.clearNote();

                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });


            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }

        },
        async changeStatus(status) {

            await UIkit.modal.confirm('Are you sure?');

            const params = {
                order_id: this.order.id,
                status: status,
                sendEmail: this.emailActive,

            };
            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.updatestatus&format=raw&" + URLparams);

            const response = await request.json();


            if (response.success) {

                this.getOrder();


                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });


            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }
        },
        async saveTracking() {

            await UIkit.modal.confirm('Are you sure?');

            const params = {
                order_id: this.order.id,
                tracking_code: this.order.tracking_code,
                tracking_link: this.order.tracking_link,
                tracking_provider: this.order.tracking_provider,
                sendEmail: this.emailTrackingActive,

            };
            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.updatetracking&format=raw&" + URLparams);

            const response = await request.json();


            if (response.success) {

                this.getOrder();


                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });


            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }
        },
        async togglePaid(){

            const params = {
                order_id: this.order.id,

            };
            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=order.togglepaid&format=raw&" + URLparams);

            const response = await request.json();


            if (response.success) {

                this.getOrder();


                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });


            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }
        },
        setData() {
            const keys = Object.keys(this.form);
            keys.forEach((jfrom) => {
                let theInput = document.getElementById(jfrom + '_data');
                if (theInput) {
                    this.form[jfrom] = theInput.innerText;
                    theInput.remove();
                }

            });
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
    components: {
        'p-inputswitch': primevue.inputswitch,
        'p-timeline': primevue.timeline,
        'p-avatar': primevue.avatar
    }
}
Vue.createApp(p2s_order_form).mount('#p2s_order_form');
