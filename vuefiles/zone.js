////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const p2s_zone_form = {
    data() {
        return {
            base_url: '',
            form: {
                jform_id: '',
                jform_zone_name: '',
                jform_country_id: '',
                jform_zone_isocode: '',
                jform_taxrate: '',
                jform_published: ''
            },
            andClose: false,
            p2s_currency: [],
            p2s_local: false

        }

    },
    mounted() {
        console.log(this.p2s_currency);
    },
    computed: {},
    async beforeMount() {

        await this.setData();


        const base_url = document.getElementById('base_url');
        this.base_url = base_url.innerText;
        base_url.remove();

        const p2s_currency = document.getElementById('currency');
        if (p2s_currency != null) {
            try {
                this.p2s_currency = JSON.parse(p2s_currency.innerText);
                p2s_currency.remove();
            } catch (err) {
            }
        }

        const p2s_locale = document.getElementById('locale');
        if (p2s_locale != null) {
            try {
                this.p2s_local = p2s_locale.innerText;
                p2s_locale.remove();
            } catch (err) {
            }
        }


    },
    methods: {

        async saveItem() {


            const params = {
                zone_id: this.form.jform_id,
                zone_name: this.form.jform_zone_name,
                country_id: this.form.jform_country_id,
                zone_isocode: this.form.jform_zone_isocode,
                taxrate: this.form.jform_taxrate,
                published: (this.form.jform_published ? 1 : 0)
            };


            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=zones.save&format=raw", {
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
                    message: 'Saved!',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });

                if (this.andClose) {
                    // if 'andClose' is true, redirect back to the discounts list page
                    window.location.href = this.base_url + 'index.php?option=com_protostore&view=zones';
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
        setData() {
            const keys = Object.keys(this.form);
            keys.forEach((jfrom) => {
                let theInput = document.getElementById(jfrom + '_data');
                if (theInput) {

                    if (this.hasJsonStructure(theInput.innerText)) {
                        this.form[jfrom] = JSON.parse(theInput.innerText);
                    } else {

                        this.form[jfrom] = theInput.innerText;

                        if (theInput.innerText === "1") {
                            this.form[jfrom] = true;
                        }
                        if (theInput.innerText === "0") {
                            this.form[jfrom] = false;
                        }


                    }
                    // theInput.remove();
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
        'p-inputswitch': primevue.inputswitch,
        'p-inputnumber': primevue.inputnumber,
        'p-inputtext': primevue.inputtext,
    }
}
Vue.createApp(p2s_zone_form).mount('#p2s_zone_form');


