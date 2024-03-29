////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const p2s_customer_form = {
    data() {
        return {
            base_url: '',
            form: {
                jform_id: '',
                jform_name: '',
                jform_email: '',
                jform_j_user_id: '',
                jform_published: '',
                jform_j_user: [],
                jform_orders: [],
                jform_total_orders: '',
                jform_order_total_integer: 0,
                jform_addresses: [],
            },
            countries: [],
            andClose: false,
            deleteConfirmMessage: ''

        }

    },
    mounted() {

    },
    computed: {},
    async beforeMount() {

        this.setData();

        const jform = document.getElementById('jform_data');
        try {
            this.jform = JSON.parse(jform.innerText);
            // jform.remove();
        } catch (err) {
        }
        const base_url = document.getElementById('base_url');
        try {
            this.base_url = base_url.innerText;
            // base_url.remove();
        } catch (err) {
        }


        const currency = document.getElementById('currency');
        try {
            this.currency = currency.innerText;
            // currency.remove();
        } catch (err) {
        }


        const locale = document.getElementById('locale');
        try {
            this.locale = locale.innerText;
            // locale.remove();
        } catch (err) {
        }
        const deleteConfirmMessage = document.getElementById('deleteConfirmMessage');
        try {
            this.deleteConfirmMessage = deleteConfirmMessage.innerText;
            deleteConfirmMessage.remove();
        } catch (err) {
        }


    },
    methods: {

        async saveItem() {

            console.log(this.form.jform_published);

            let customerSave = new Object();

            customerSave.id = this.form.jform_id;
            customerSave.name = this.form.jform_name;
            customerSave.email = this.form.jform_email;
            customerSave.j_user_id = this.form.jform_j_user_id;
            customerSave.published = (this.form.jform_published ? 1 : 0);

            const params = {
                customer: JSON.stringify(customerSave)
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=customer.save&format=raw&" + URLparams);

            const response = await request.json();


            if (response.success) {

                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });

                if (this.andClose) {
                    // if 'andClose' is true, redirect back to the discounts list page
                    window.location.href = this.base_url + 'index.php?option=com_protostore&view=customers';
                } else {
                    // if 'andClose' is still false, the user wants to stay on the page.
                    // this line makes sure that a new item gets the ID appended to the URL

                }

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }


        },
        editAddress(address) {
            address.edit = true;
            this.getZones(address)
        },
        async saveAddress(address) {
            address.edit = false;

            delete address.zones;

            const params = {
                address: JSON.stringify(address)
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.save&format=raw&" + URLparams);

            const response = await request.json();

            if (response.success) {
                this.updateAddresses();
                UIkit.notification({
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });

            }
        },
        async updateAddresses() {
            const params = {
                customer_id: this.form.jform_id,
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.getCustomerAddressList&format=raw&" + URLparams);

            const response = await request.json();

            if (response.success) {
                this.form.jform_addresses = response.data;
            }
        },
        async getZones(address) {
            const params = {
                country_id: address.country
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.getZones&format=raw&" + URLparams);

            const response = await request.json();

            if (response.success) {
                address.zones = response.data;
            }
        },

        async launchDeleteDialog() {
            await UIkit.modal.confirm('<h3>' + this.deleteConfirmMessage + '</h3>');


            const params = {
                'user_id': this.form.jform_j_user_id,
                'customer_id': this.form.jform_id
            };

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=customer.delete&format=raw", {
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

                UIkit.notification({
                    message: 'done',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });

                window.location.href = this.base_url + 'index.php?option=com_protostore&view=customers';

            }

        },
        setData() {
            const keys = Object.keys(this.form);
            keys.forEach((jfrom) => {
                let theInput = document.getElementById(jfrom + '_data');
                if (theInput) {

                    if (this.hasJsonStructure(theInput.innerText)) {
                        this.form[jfrom] = JSON.parse(theInput.innerText);
                    } else {

                        this.form[jfrom] = theInput.innerText;

                        if (theInput.innerText == 1) {
                            this.form[jfrom] = true;
                        }
                        if (theInput.innerText == 0) {
                            this.form[jfrom] = false;
                        }


                    }
                    theInput.remove();
                }

            });
        },
        hasJsonStructure(str) {
            if (typeof str !== 'string') return false;
            try {
                const result = JSON.parse(str);
                const type = Object.prototype.toString.call(result);
                return type === '[object Object]'
                    || type === '[object Array]';
            } catch (err) {
                return false;
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
    components: {
        'p-inputswitch': primevue.inputswitch
    }
}
Vue.createApp(p2s_customer_form).mount('#p2s_customer_form');


