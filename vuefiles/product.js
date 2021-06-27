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
                jform_state: false,
                jform_taxable: false,
                jform_show_discount: false,
                jform_discount: '',
                jform_teaserimage: '',
                jform_fullimage: '',
                jform_shipping_mode: '',
                jform_publish_up_date: '',
                jform_tags: [],
                jform_variants: [],
                variantLabels: [],
                variantsList: [],
                variantsListLocal: []
            },
            variantsSet: false,
            showVariantsBody: true,
            andClose: false

        }

    },
    created() {


    },
    mounted() {
        if (this.form.jform_variants.length > 0) {
            this.variantsSet = true;
        }
        if (this.form.variantLabels.length > 0 && this.form.variantsListLocal.length == 0) {
            this.runCartesian();
        }
    },
    computed: {
        variantsSet() {
            if (this.form.jform_variants[0] && this.showVariantValuesBlock) {
                return true;
            }
            return false;
        },
        showVariantValuesBlock() {
            if (this.form.jform_variants.length > 0 && this.variantsSet) {
                return true;
            }
            return false;
        },
        showVariantItemsBlock() {
            if (this.form.variantsListLocal.length > 0) {
                return true;
            }
            return false;
        },
        sellPrice(){
            return this.form.jform_base_price - this.form.jform_discount;
        }
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

        const jform_show_discount = document.getElementById('jform_show_discount_data');
        this.form.jform_show_discount = (jform_show_discount.innerText == 'true' ? true : false);
        jform_show_discount.remove();

        const jform_discount = document.getElementById('jform_discount_data');
        this.form.jform_discount = jform_discount.innerText;
        jform_discount.remove();

        const jform_category = document.getElementById('jform_category_data');
        this.form.jform_category = jform_category.innerText;
        jform_category.remove();

        const jform_featured = document.getElementById('jform_featured_data');
        this.form.jform_featured = (jform_featured.innerText == 'true' ? true : false);
        jform_featured.remove();

        const jform_state = document.getElementById('jform_state_data');
        this.form.jform_state = (jform_state.innerText == 'true' ? true : false);
        jform_state.remove();

        const jform_shipping_mode = document.getElementById('jform_shipping_mode_data');
        this.form.jform_shipping_mode = jform_shipping_mode.innerText;
        jform_shipping_mode.remove();

        const jform_base_price = document.getElementById('jform_base_price_data');
        this.form.jform_base_price = jform_base_price.innerText;
        jform_base_price.remove();

        const jform_variants = document.getElementById('jform_variants');
        this.form.jform_variants = JSON.parse(jform_variants.innerText);
        jform_variants.remove();

        const jform_variantLabels = document.getElementById('jform_variantLabels');
        this.form.variantLabels = JSON.parse(jform_variantLabels.innerText);
        jform_variantLabels.remove();

        const jform_variantsListLocal = document.getElementById('jform_variantsListLocal');
        this.form.variantsListLocal = JSON.parse(jform_variantsListLocal.innerText);
        jform_variantsListLocal.remove();


    },
    methods: {

        logIt() {
            console.log(this.form);
        },
        addVariant() {
            this.form.jform_variants.push('');
        },
        removeVariant(i) {
            this.form.jform_variants.splice(i, 1);
        },
        async editVariants() {

            await UIkit.modal.confirm('Are You sure? This will reset all variant data!');

            this.form.variantsList = [];
            this.form.variantsListLocal = [];
            this.form.variantLabels = [];
            this.variantsSet = false;

        },
        async editVariantValues() {

            await UIkit.modal.confirm('Are You sure? This will reset all variant data!');

            this.form.variantsList = [];
            this.form.variantsListLocal = [];

        },
        setVariants() {
            this.variantsSet = true;
        },
        runCartesian() {
            this.form.variantsListLocal = [];
            this.form.variantsList = this.cartesianProduct(this.form.variantLabels);

            this.form.variantsList.forEach((variant, index) => {

                let newName = variant.join(' / ');
                let itsDefault = false;
                if (index === 0) {
                    itsDefault = true;
                }
                this.form.variantsListLocal.push({
                    identifier: variant,
                    name: newName,
                    active: true,
                    default: itsDefault,
                    price: '',
                    stock: 0,
                    sku: '',
                })
            });

            if (this.form.variantsListLocal.length > 0) {
                this.showVariantItemsBlock = true;
            }

        },
        cartesianProduct(arr) {
            return arr.reduce(function (a, b) {
                return a.map(function (x) {
                    return b.map(function (y) {
                        return x.concat([y]);
                    })
                }).reduce(function (a, b) {
                    return a.concat(b)
                }, [])
            }, [[]])
        },

        setVariantDefault(itemIndex) {

            this.form.variantsListLocal.forEach((variant, index) => {
                variant.default = false;
                if (itemIndex === index) {
                    variant.default = true;
                    if (!variant.active) {
                        variant.active = true;
                    }
                }
            });
        },
        checkVariantDefault(itemIndex) {
            this.form.variantsListLocal.forEach((variant, index) => {
                if (itemIndex === index) {
                    if (variant.default) {
                        variant.active = true;
                        return false;
                    }
                }
            });
        },
        async checkVariant() {
            //
            // if (this.showVariantsBody === true) {
            //     // await UIkit.modal.confirm('Are You sure? This will erase all variant data!');
            //     // this.form.variantsList = [];
            //     // this.form.variantsListLocal = [];
            //     // this.form.variantLabels = [];
            //     // this.variantsSet = false;
            //
            //     UIkit.modal.confirm('UIkit confirm!').then(function () {
            //         console.log('accepted');
            //         this.showVariantsBody = false;
            //     }, function () {
            //         console.log('rejected');
            //         this.showVariantsBody = true;
            //         console.log(this.showVariantsBody);
            //     });
            //
            // }
        },
        toggle() {
            console.log(this.hasErroraccess);
            this.hasErroraccess = !this.hasErroraccess;
        },

        saveItem() {
            this.form.jform_long_description = this.getFrameContents('jform_long_description');
            this.form.jform_short_description = this.getFrameContents('jform_short_description');
            this.form.jform_teaserimage = document.getElementById("jform_teaserimage").value;
            this.form.jform_fullimage = document.getElementById("jform_fullimage").value;
            this.form.jform_publish_up_date = document.getElementById("jform_publish_up_date").value;
            this.form.jform_manage_stock = document.getElementById("jform_manage_stock").value;
            this.form.jform_state = document.getElementById("jform_state").value;
            this.form.jform_featured = document.getElementById("jform_featured").value;
            this.form.jform_taxable = document.getElementById("jform_taxable").value;
            this.form.jform_discount = document.getElementById("jform_discount").value;
            // this.form.jform_shipping_mode = document.getElementById("jform_shipping_mode").value;

            // this.hasErroraccess = true;

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
        'p-chips': primevue.chips,
        'p-inputnumber': primevue.inputnumber
    }
}

Vue.createApp(p2s_product_form).mount('#p2s_product_form')
