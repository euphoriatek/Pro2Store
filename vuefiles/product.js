const p2s_product_form = {
    data() {
        return {
            base_url: '',
            form: {
                itemid: '',
                jform_title: '',
                jform_short_description: '',
                jform_long_description: '',
                jform_base_price: '',
                jform_sku: '',
                jform_category: '',
                jform_manage_stock: true,
                jform_stock: '',
                jform_featured: false,
                jform_state: false,
                jform_taxable: false,
                jform_show_discount: false,
                jform_discount: '',
                jform_teaserimage: '',
                jform_fullimage: '',
                jform_shipping_mode: '',
                jform_flatfee: '',
                jform_publish_up_date: '',
                jform_tags: [],
                jform_options: [],
                jform_variants: [],
                variantLabels: [],
                variantsList: [],
                variantsListLocal: []
            },
            available_tags: [],
            available_options: [],
            option_for_edit: [],
            p2s_currency: [],
            p2s_local: '',
            andClose: false,
            variantsSet: false
        }

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
        showVariantItemsBlock() {

            if (this.form.variantsListLocal.length > 0) {
                return true;
            } else {
                return false;
            }

        },
        sellPrice() {

            const options = {
                maximumFractionDigits: 2,
                currency: this.p2s_currency.iso,
                style: "currency",
                currencyDisplay: "symbol"
            }

            return this.localStringToNumber(this.form.jform_base_price - this.form.jform_discount).toLocaleString(undefined, options);

        },
        modifierValueInputType() {
            if (this.option_for_edit.modifiertype === "perc") {
                return "[0-9]*";
            } else if (this.option_for_edit.modifiertype === "amount") {
                return "^[0-9]+(\.[0-9]{1,2})?$"
            }
        }
    },
    async beforeMount() {

        const base_url = document.getElementById('base_url');
        try {
            this.base_url = base_url.innerText;
            // base_url.remove();
        } catch (err) {
        }

        const jform_title = document.getElementById('jform_title_data');
        try {
            this.form.jform_title = jform_title.innerText;
            // jform_title.remove();
        } catch (err) {
        }

        const itemid = document.getElementById('jform_joomla_item_id_data');
        try {
            this.form.itemid = itemid.innerText;
            // itemid.remove();
        } catch (err) {
        }

        const jform_manage_stock = document.getElementById('jform_manage_stock_data');
        try {
            this.form.jform_manage_stock = (jform_manage_stock.innerText == 1);
            //  jform_manage_stock.remove();
        } catch (err) {
        }

        const jform_stock = document.getElementById('jform_stock_data');
        try {
            this.form.jform_stock = jform_stock.innerText;
            //  jform_stock.remove();
        } catch (err) {
        }

        const jform_taxable = document.getElementById('jform_taxable_data');
        try {
            this.form.jform_taxable = (jform_taxable.innerText == 1);
            //  jform_taxable.remove();
        } catch (err) {
        }

        const jform_show_discount = document.getElementById('jform_show_discount_data');
        try {
            this.form.jform_show_discount = (jform_show_discount.innerText == 1);
            //   jform_show_discount.remove();
        } catch (err) {
        }

        const jform_discount = document.getElementById('jform_discount_data');
        try {
            this.form.jform_discount = jform_discount.innerText;
            //   jform_discount.remove();
        } catch (err) {
        }

        const jform_sku = document.getElementById('jform_sku_data');
        try {
            this.form.jform_sku = jform_sku.innerText;
            //  jform_sku.remove();
        } catch (err) {
        }

        const jform_category = document.getElementById('jform_catid_data');
        try {
            this.form.jform_category = jform_category.innerText;
            //  jform_category.remove();
        } catch (err) {
        }

        const jform_featured = document.getElementById('jform_featured_data');
        try {
            this.form.jform_featured = (jform_featured.innerText == 1);
            //  jform_featured.remove();
        } catch (err) {
        }

        const jform_state = document.getElementById('jform_state_data');
        try {
            this.form.jform_state = (jform_state.innerText == 1);
            //  jform_state.remove();
        } catch (err) {
        }

        const jform_shipping_mode = document.getElementById('jform_shipping_mode_data');
        try {
            this.form.jform_shipping_mode = jform_shipping_mode.innerText;
            //  jform_shipping_mode.remove();
        } catch (err) {
        }

        const jform_flatfee = document.getElementById('jform_flatfee_data');
        try {
            this.form.jform_flatfee = jform_flatfee.innerText
            //  jform_flatfee.remove();
        } catch (err) {
        }

        const jform_base_price = document.getElementById('jform_base_price_data');
        try {
            this.form.jform_base_price = parseFloat(jform_base_price.innerText);
            //  jform_base_price.remove();
        } catch (err) {
        }

        const jform_options = document.getElementById('jform_options');
        try {
            this.form.jform_options = JSON.parse(jform_options.innerText);
            //  jform_options.remove();
        } catch (err) {
        }

        const available_options = document.getElementById('available_options_data');
        try {
            this.available_options = JSON.parse(available_options.innerText);
            //  available_options.remove();
        } catch (err) {
        }

        const jform_variants = document.getElementById('jform_variants_data');
        try {
            if(jform_variants.innerText != 'null') {
                this.form.jform_variants = JSON.parse(jform_variants.innerText);
            } else {
                this.form.jform_variants = new Array(0);
            }

            //  jform_variants.remove();
        } catch (err) {
            this.form.jform_variants = new Array(0);
        }


        const jform_variantLabels = document.getElementById('jform_variantLabels_data');
        try {

            if(jform_variantLabels.innerText != 'null') {
                this.form.variantLabels = JSON.parse(jform_variantLabels.innerText);
            } else {
                this.form.jform_variants = new Array(0);
            }
            // jform_variantLabels.remove();
        } catch (err) {
            this.form.variantLabels = new Array(0);
        }


        const jform_variantsListLocal = document.getElementById('jform_variantList_data');
        try {

            if(jform_variantLabels.innerText != 'null') {
                this.form.variantsListLocal = JSON.parse(jform_variantsListLocal.innerText);
            } else {
                this.form.variantsListLocal = new Array(0);
            }
            //  jform_variantsListLocal.remove();
        } catch (err) {
            this.form.variantsListLocal = new Array(0);
        }

        const p2s_currency = document.getElementById('p2s_currency');
        try {
            this.p2s_currency = JSON.parse(p2s_currency.innerText);
            // p2s_currency.remove();
        } catch (err) {
        }


        const p2s_locale = document.getElementById('p2s_locale');
        try {
            this.p2s_local = p2s_locale.innerText;
            // p2s_local.remove();
        } catch (err) {
        }

        const available_tags = document.getElementById('available_tags');
        try {
            this.available_tags = JSON.parse(available_tags.innerText);
            // available_tags.remove();
        } catch (err) {
        }

    },
    methods: {

        logIt() {
            // console.log("jform_base_price", this.form.jform_base_price);
            // console.log("jform_discount", this.form.jform_discount);
        },

        /**
         * VARIANTS
         */
        addVariant() {
            this.form.jform_variants.push('');
        },
        removeVariant(i) {
            this.form.jform_variants.splice(i, 1);
        },
        async variantsStartOver() {

            await UIkit.modal.confirm('Are You sure? This will reset all variant data!');

            this.form.jform_variants = [];
            this.form.variantsList = [];
            this.form.variantsListLocal = [];
            this.form.variantLabels = [];

        },
        async editVariantValues() {

            await UIkit.modal.confirm('Are You sure? This will reset all variant data!');

            this.form.variantsList = [];
            this.form.variantsListLocal = [];

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
        formatToCurrency(itemPrice) {

            const value = itemPrice;
            const options = {
                maximumFractionDigits: 2,
                currency: this.p2s_currency.iso,
                style: "currency",
                currencyDisplay: "symbol"
            }

            itemPrice = this.localStringToNumber(value).toLocaleString(undefined, options);


        },
        localStringToNumber(s) {
            return Number(String(s).replace(/[^0-9.-]+/g, ""))
        },

        /**
         * OPTIONS
         */

        addOptionOfType(i) {
            this.form.jform_options.push({
                optiontype: this.available_options[i].identifier,
                optiontypename: this.available_options[i].name
            })
        },
        openEditoptionModal(option) {
            this.option_for_edit = option;
            UIkit.modal('#editProductOption').show();
        },
        processModifierValue(option) {
            if (option.modifiertype === "perc") {
                option.modifiervalue_translated = option.modifiervalueFloat + '%';
            } else if (option.modifiertype === "amount") {
                const formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: this.p2s_currency.iso
                });
                option.modifiervalue_translated = formatter.format(option.modifiervalueFloat); /* $2,500.00 */
            }
        },
        async removeOption(i) {
            // todo - translate
            await UIkit.modal.confirm('Are you sure?');
            this.form.jform_options.splice(i, 1);
        },
        async saveItem() {


            this.form.jform_long_description = this.getFrameContents('jform_long_description');
            this.form.jform_short_description = this.getFrameContents('jform_short_description');
            this.form.jform_teaserimage = document.getElementById("jform_teaserimage").value;
            this.form.jform_fullimage = document.getElementById("jform_fullimage").value;
            this.form.jform_publish_up_date = document.getElementById("jform_publish_up_date").value;


            this.form.jform_tags = [];
            for (var option of document.getElementById("jform_tags").options) {
                this.form.jform_tags.push(option.value);
            }

            // todo - VALIDATE EVERYTHING

            console.log(this.form);

            const params = {
                'itemid': this.form.itemid,
                'title': this.form.jform_title,
                'introtext': this.form.jform_short_description,
                'fulltext': this.form.jform_long_description,
                'category': this.form.jform_category,
                'base_price': this.form.jform_base_price,
                'tags': this.form.jform_tags,
                'sku': this.form.jform_sku,
                'stock': this.form.jform_stock,
                'manage_stock': (this.form.jform_manage_stock ? 1 : 0),
                'featured': (this.form.jform_featured ? 1 : 0),
                'state': (this.form.jform_state ? 1 : 0),
                'taxable': (this.form.jform_taxable ? 1 : 0),
                'discount': this.form.jform_discount,
                'teaserimage': this.form.jform_teaserimage,
                'fullimage': this.form.jform_fullimage,
                'shipping_mode': this.form.jform_shipping_mode,
                'flatfee': this.form.jform_flatfee,
                'publish_up_date': this.form.jform_publish_up_date,
                'options': JSON.stringify(this.form.jform_options),
                'variants': JSON.stringify(this.form.jform_variants),
                'variantLabels': JSON.stringify(this.form.variantLabels),
                'variantList': JSON.stringify(this.form.variantsListLocal)
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.save&format=raw&" + URLparams);

            const response = await request.json();
            console.log(response);
            if (response.success) {

                console.log(response)

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }


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
        'p-chips': primevue.chips,
        'p-inputnumber': primevue.inputnumber,
        'p-multiselect': primevue.multiselect
    }
}
Vue.createApp(p2s_product_form).mount('#p2s_product_form');
