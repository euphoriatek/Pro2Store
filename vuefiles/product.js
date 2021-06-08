const p2s_product_form = {
    data() {
        return {
            form: {
                jform_title: '',
                jform_short_description: '',
                jform_long_description: '',
                jform_base_price: '',
                jform_category: '',
                jform_manage_stock: true,
                jform_featured: false,
                jform_taxable: false,
                jform_discount: false,
                jform_teaserimage: '',
                jform_fullimage: '',
                jform_shipping_mode: '',
                jform_publish_up_date: '',
                jform_tags: [],
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

        const jform_title = document.getElementById('jform_title_data');
        this.form.jform_title = jform_title.innerText;
        jform_title.remove();

        const jform_manage_stock = document.getElementById('jform_manage_stock_data');
        this.form.jform_manage_stock = (jform_manage_stock.innerText == 'true' ? true : false);
        jform_manage_stock.remove();

        const jform_taxable = document.getElementById('jform_taxable_data');
        this.form.jform_taxable = (jform_taxable.innerText == 'true' ? true : false);
        jform_taxable.remove();

        const jform_discount = document.getElementById('jform_discount_data');
        this.form.jform_discount = (jform_discount.innerText == 'true' ? true : false);
        jform_discount.remove();

        const jform_category = document.getElementById('jform_category_data');
        this.form.jform_category = jform_category.innerText;
        jform_category.remove();

        const jform_featured = document.getElementById('jform_featured_data');
        this.form.jform_featured = (jform_featured.innerText == 'true' ? true : false);
        jform_featured.remove();

        const jform_shipping_mode = document.getElementById('jform_shipping_mode_data');
        this.form.jform_shipping_mode = jform_shipping_mode.innerText;
        jform_shipping_mode.remove();

        const jform_base_price = document.getElementById('jform_base_price_data');
        this.form.jform_base_price = jform_base_price.innerText;
        jform_base_price.remove();


    },
    methods: {

        saveItem() {
            this.form.jform_long_description = this.getFrameContents('jform_long_description');
            this.form.jform_short_description = this.getFrameContents('jform_short_description');
            this.form.jform_teaserimage = document.getElementById("jform_teaserimage").value;
            this.form.jform_fullimage = document.getElementById("jform_fullimage").value;
            this.form.jform_publish_up_date = document.getElementById("jform_publish_up_date").value;
            this.form.jform_manage_stock = document.getElementById("jform_manage_stock").value;
            this.form.jform_featured = document.getElementById("jform_featured").value;
            this.form.jform_taxable = document.getElementById("jform_taxable").value;
            this.form.jform_discount = document.getElementById("jform_discount").value;
            // this.form.jform_shipping_mode = document.getElementById("jform_shipping_mode").value;

            this.form.jform_tags = [];
            for (var option of document.getElementById("jform_tags").options) {
                this.form.jform_tags.push(option.value);
            }

            console.log(this.form);

        },

        getFrameContents(elementId) {
            const iFrame = document.getElementById(elementId + '_ifr');
            let iFrameBody;
            if (iFrame.contentDocument) { // FF
                iFrameBody = iFrame.contentDocument.getElementById('tinymce');
            } else if (iFrame.contentWindow) { // IE
                iFrameBody = iFrame.contentWindow.document.getElementById('tinymce');
            }
            return iFrameBody.innerHTML;
        }
    },
    components: {
        'p-inputswitch': primevue.inputswitch,
        'p-inputnumber': primevue.inputnumber
    }
}

Vue.createApp(p2s_product_form).mount('#p2s_product_form')
