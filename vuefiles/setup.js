////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const p2s_setup = {
    data() {
        return {
            base_url: '',
            currency: '',
            locale: '',
            shopName: '',
            shopEmail: '',
            currencies: '',
            selectedCurrency: 14,
            countries: '',
            selectedCountry: 103,
            createCheckout: false,
            createConfirmation: false,
            createTandcs: false,
            createCancel: false,
        };
    },
    created() {

    },
    mounted() {

    },
    computed() {
    },
    async beforeMount() {

        const base_url = document.getElementById('base_url');
        try {
            this.base_url = base_url.innerText;
            base_url.remove();
        } catch (err) {
        }

        const currency = document.getElementById('currency');
        try {
            this.currency = JSON.parse(currency.innerText);
            currency.remove();
        } catch (err) {
        }


        const locale = document.getElementById('locale');
        try {
            this.locale = locale.innerText;
            locale.remove();
        } catch (err) {
        }

        const currencies = document.getElementById('currencies_data');
        try {
            this.currencies = JSON.parse(currencies.innerText);
            // currencies.remove();
        } catch (err) {
        }

        const countries = document.getElementById('countries_data');
        try {
            this.countries = JSON.parse(countries.innerText);
            // countries.remove();
        } catch (err) {
        }


    },
    methods: {
        async submitSetup() {
            const params = {
                'shopName': this.shopName,
                'shopEmail': this.shopEmail,
                'selectedCurrency': this.selectedCurrency,
                'selectedCountry': this.selectedCountry,
                'createCheckout': this.createCheckout,
                'createConfirmation': this.createConfirmation,
                'createTandcs': this.createTandcs,
                'createCancel': this.createCancel
            };

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=setup.init&format=raw", {
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
                    message: 'Done',
                    status: 'success',
                    pos: 'bottom-right',
                    timeout: 5000
                });
                window.location.href = this.base_url + 'index.php?option=com_protostore';
            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }

        }
    },
    components: {
        'p-inputswitch': primevue.inputswitch
    }
}

Vue.createApp(p2s_setup).mount('#p2s_setup')
