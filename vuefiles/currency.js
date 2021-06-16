const p2s_currency_form = {
    data() {
        return {
            form: {
                jform_name: '',
                jform_iso: '',
                jform_currencysymbol: '',
                jform_rate: '',
                jform_default: '',
                jform_published: true
            },
            andClose: false
        };
    },
    created() {

    },
    mounted() {

    },
    computed() {
    },
    async beforeMount() {

        const jform_name = document.getElementById('jform_name_data');
        this.form.jform_name = jform_name.innerText;
        jform_name.remove();

        const jform_iso = document.getElementById('jform_iso_data');
        this.form.jform_iso = jform_iso.innerText;
        jform_iso.remove();

        const jform_currencysymbol = document.getElementById('jform_currencysymbol_data');
        this.form.jform_currencysymbol = jform_currencysymbol.innerText;
        jform_currencysymbol.remove();

        const jform_rate = document.getElementById('jform_rate_data');
        this.form.jform_rate = jform_rate.innerText;
        jform_rate.remove();

        const jform_default = document.getElementById('jform_default_data');
        this.form.jform_default = (jform_default.innerText === 'true');
        jform_default.remove();

        const jform_published = document.getElementById('jform_published_data');
        this.form.jform_published = (jform_published.innerText === 'true');
        jform_published.remove();



    },
    methods: {

        toggle() {
            console.log(this.hasErroraccess);
            this.hasErroraccess = !this.hasErroraccess;
        },

        saveItem() {
            // this.form.jform_long_description = this.getFrameContents('jform_long_description');
            // this.form.jform_short_description = this.getFrameContents('jform_short_description');
            // this.form.jform_teaserimage = document.getElementById("jform_teaserimage").value;
            // this.form.jform_fullimage = document.getElementById("jform_fullimage").value;
            // this.form.jform_publish_up_date = document.getElementById("jform_publish_up_date").value;
            // this.form.jform_manage_stock = document.getElementById("jform_manage_stock").value;
            // this.form.jform_featured = document.getElementById("jform_featured").value;
            // this.form.jform_taxable = document.getElementById("jform_taxable").value;
            // this.form.jform_discount = document.getElementById("jform_discount").value;
            // // this.form.jform_shipping_mode = document.getElementById("jform_shipping_mode").value;
            //
            // // this.hasErroraccess = true;
            //
            // this.form.jform_tags = [];
            // for (var option of document.getElementById("jform_tags").options) {
            //     this.form.jform_tags.push(option.value);
            // }

            console.log(this.form);

        },

    },
    components: {
        'p-inputswitch': primevue.inputswitch
    }
}

Vue.createApp(p2s_currency_form).mount('#p2s_currency_form')
