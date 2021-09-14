const p2s_discount_form = {
    data() {
        return {
            base_url: '',
            form: {
                jform_id: '',
                jform_name: '',
                jform_coupon_code: '',
                jform_amount: '',
                jform_percentage: '',
                jform_discount_type: '',
                jform_published: '',
                jform_expiry_date: '',
                jform_created: '',
                jform_modified: '',
                jform_created_by: '',
                jform_modified_by: '',
                jform_discount_type_string: '',
            },
            andClose: false,
            p2s_currency: [],
            p2s_locale: false

        }

    },
    mounted() {
        console.log(this.p2s_currency);
    },
    computed: {},
    async beforeMount() {
       await this.setData();
        this.form.jform_amount = this.form.jform_amount / 100;
        const base_url = document.getElementById('base_url');
        this.base_url = base_url.innerText;
        // base_url.remove();

        const currency = document.getElementById('currency');
        if (currency) {
            this.p2s_currency = JSON.parse(currency.innerText);
        }
        // currency.remove();

        const locale = document.getElementById('locale');
        if (locale) {
            this.p2s_locale = locale.innerText;
        }
        // locale.remove();


    },
    methods: {

        async saveItem() {

            this.form.jform_expiry_date = document.getElementById("jform_expiry_date").getAttribute('data-alt-value');

            const params = {
                itemid: this.form.jform_id,
                name: this.form.jform_name,
                coupon_code: this.form.jform_coupon_code,
                amount: this.form.jform_amount * 100,
                percentage: this.form.jform_percentage,
                discount_type: this.form.jform_discount_type,
                expiry_date: this.form.jform_expiry_date,
                published: (this.form.jform_published ? 1 : 0)
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=discount.save&format=raw&" + URLparams);

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
                    window.location.href = this.base_url + 'index.php?option=com_protostore&view=discounts';
                } else {
                    // if 'andClose' is still false, the user wants to stay on the page.
                    // this line makes sure that a new item gets the ID appended to the URL
                    const url = window.location.href;
                    if (url.indexOf('&id=') == -1) {
                        history.replaceState('', '', url + '&id=' + response.data.id);
                    }

                    // we also need to make sure that the next save action doesn't trigger a create... we do this by adding the id to the form array
                    this.form.jform_id = response.data.id;


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
        async setData() {
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
        'p-inputnumber': primevue.inputnumber,
        'p-inputtext': primevue.inputtext
    }
}
Vue.createApp(p2s_discount_form).mount('#p2s_discount_form');

