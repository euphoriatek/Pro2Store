////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const p2s_product_form = {
    data() {
        return {
            base_url: '',
            form: {
                itemid: 0,
                jform_title: '',
                jform_catid: '',
                jform_short_description: '',
                jform_long_description: '',
                jform_access: '',
                jform_base_price: 0,
                jform_sku: '',
                jform_manage_stock: true,
                jform_stock: '',
                jform_featured: false,
                jform_state: false,
                jform_taxable: false,
                jform_show_discount: false,
                jform_discount: 0,
                jform_discount_type: "amount",
                jform_teaserimage: '',
                jform_fullimage: '',
                jform_shipping_mode: '',
                jform_flatfee: 0,
                jform_publish_up_date: '',
                jform_product_type: '',
                jform_tags: [],
                jform_options: [],
                jform_variants: [],
                jform_variantList: [],
                files: []
            },
            product_id: 0,
            product_type: 1,
            custom_fields: [],
            available_tags: [],
            option_for_edit: [],
            p2s_currency: [],
            p2s_local: '',
            discount_type: 1,
            andClose: false,
            variantsSet: false,
            file_for_edit: {},
            newOptionTypeName: '',
            newOptionTypeType: 'Dropdown',
            showNewOptionTypeNameWarning: false,
            // sellPrice: 0,
            variants_loading: false,
            setSavedClass: false,
            //media manager
            media_view: 'table',
            selected_images: [],
            selected_folders: [],
            folderTree: [],
            breadcrumbs: [],
            currentParent: 0,
            currentFolderId: 0,
            mediaLoading: false,
            // strings
            COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED: '',
            COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT: '',
            COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE: '',
            COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT: '',
            COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL: ''
        }

    },
    created() {
        emitter.on('p2s_product_file_upload', this.fileUploaded);

    },
    computed: {
        currentDirectory() {
            return this.folderTree.find(o => o.id === this.currentParent);
        },
        modifierValueInputType() {
            if (this.option_for_edit.modifiertype === "perc") {
                return "[0-9]*";
            } else if (this.option_for_edit.modifiertype === "amount") {
                return "^[0-9]+(\.[0-9]{1,2})?$"
            }
        },
        singleSelection() {
            if (this.selected_images.length === 1 && this.selected_folders.length === 0) {
                return false;
            } else {
                return true;
            }
        },
        singleFolderSelection() {
            if (this.selected_folders.length === 1 && this.selected_images.length === 0) {
                return false;
            } else {
                return true;
            }
        },
        somethingIsSelected() {
            if (this.selected_folders.length > 0 || this.selected_images.length > 0) {
                return true;
            } else {
                return false;
            }
        },
        checkDeleteDisabled() {

            if (this.selected_images.length === 0 && this.selected_folders.length === 0) {
                return true;
            }


            return false;
        },
        checkEditDisabled() {

            if ((this.selected_images.length === 0 && this.selected_folders.length === 0) || (this.selected_images.length > 1 && this.selected_folders.length > 1)) {
                return true;
            }


            return false;
        },
        sellPrice(){
            const options = {
                maximumFractionDigits: 2,
                currency: this.p2s_currency.iso,
                style: "currency",
                currencyDisplay: "symbol"
            }

            console.log(this.form.jform_base_price);
            console.log(this.form.jform_discount);
            console.log(this.form.jform_discount_type);

            if (this.form.jform_discount_type === "amount") {
                return this.localStringToNumber(this.form.jform_base_price - this.form.jform_discount).toLocaleString(this.p2s_local, options);
            } else {
                // work out the percentage
                const discount = (this.form.jform_base_price / 100) * this.form.jform_discount;
                return this.localStringToNumber(this.form.jform_base_price - discount).toLocaleString(this.p2s_local, options);
            }
        }
    },
    async beforeMount() {

        const base_url = document.getElementById('base_url');
        if (base_url != null) {
            try {
                this.base_url = base_url.innerText;
                base_url.remove();
            } catch (err) {
            }
        }

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

        const folderTree = document.getElementById('folderTree_data');
        if (folderTree != null) {
            try {
                this.folderTree = JSON.parse(folderTree.innerText);
                // folderTree.remove();
            } catch (err) {
            }
        }


        const default_category = document.getElementById('default_category_data');
        if (default_category != null) {
            try {
                this.form.jform_category = default_category.innerText;
                default_category.remove();
            } catch (err) {
            }
        }
        const custom_fields = document.getElementById('custom_fields_data');
        if (custom_fields != null) {
            try {
                this.custom_fields = JSON.parse(custom_fields.innerText);
                // custom_fields.remove();
            } catch (err) {
            }
        }

        const itemid = document.getElementById('jform_joomla_item_id_data');
        if (itemid != null) {
            // for product edit... do everything inside this if block since we have an item id
            try {
                this.form.itemid = itemid.innerText;
                // itemid.remove();
            } catch (err) {
            }


            // set images
            const images = document.getElementById('jform_images_data');
            if (images != null) {
                try {
                    const imageArray = JSON.parse(images.innerText);

                    this.form.jform_teaserimage = imageArray.image_intro;
                    this.form.jform_fullimage = imageArray.image_fulltext;
                    // images.remove();
                } catch (err) {
                }
            }
            this.setData();
            const COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED = document.getElementById('COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED');
            if (COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED != null) {
                try {
                    this.COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED = COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED.innerText;
                    COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED.remove();
                } catch (err) {
                }
            }
        }
        const available_tags = document.getElementById('available_tags_data');
        if (available_tags != null) {
            try {
                this.available_tags = JSON.parse(available_tags.innerText);
                // available_tags.remove();
            } catch (err) {
            }
        }
        this.form.jform_options = [];
        const options = document.getElementById('options');
        if (options != null) {
            try {
                this.form.jform_options = JSON.parse(options.innerText);
                options.remove();
            } catch (err) {
                this.form.jform_options = [];
            }
        } else{
            this.form.jform_options = [];
        }

        // LANGUANGE STRINGS
        const COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT = document.getElementById('COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT');
        if (COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT != null) {
            try {
                this.COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT = COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT.innerText;
                COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT.remove();
            } catch (err) {
            }
        }
        const COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE = document.getElementById('COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE');
        if (COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE != null) {
            try {
                this.COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE = COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE.innerText;
                COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE.remove();
            } catch (err) {
            }
        }
        const COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT = document.getElementById('COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT');
        if (COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT != null) {
            try {
                this.COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT = COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT.innerText;
                COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT.remove();
            } catch (err) {
            }
        }
        const COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL = document.getElementById('COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL');
        if (COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL != null) {
            try {
                this.COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL = COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL.innerText;
                COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL.remove();
            } catch (err) {
            }
        }
        const COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL = document.getElementById('COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL');
        if (COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL != null) {
            try {
                this.COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL = COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL.innerText;
                COM_PROTOSTORE_MEDIA_MANAGER_DROPZONE_LABEL.remove();
            } catch (err) {
            }
        }
    },
    mounted(){

        if (!this.form.jform_discount_type) {
            this.form.jform_discount_type = "amount";
        }

    },
    methods: {

        logIt() {
            console.log(this.custom_fields);
        },


        /**
         * TAGS
         */

        addTagFromChip(tag, i) {
            this.form.jform_tags.push(tag);
            this.available_tags.splice(i, 1);
        },
        addBackToAvailable(e) {
            this.available_tags.push(e.value[0]);
        },

        /**
         * VARIANTS
         */
        addVariant() {
            let newVariant = {
                id: 0,
                product_id: this.form.itemid,
                name: '',
                labels: []
            }

            this.form.jform_variants.push(newVariant);
        },
        async removeVariant(i) {
            await UIkit.modal.confirm(this.COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE);
            this.form.jform_variants[i].labels = [];
            this.form.jform_variants.splice(i, 1);
            await this.setVariants();
            await this.saveItem();
        },
        async updateVariantValues() {
            this.variants_loading = true;
            clearTimeout(this.debounce)
            this.debounce = setTimeout(() => {
                this.saveVariantValues();
            }, 1600)
        },
        async setVariants() {
            this.variants_loading = true;


            const params = {
                'variants': this.form.jform_variants,
                'variantList': this.form.jform_variantList,
                'itemid': this.form.itemid,
                'base_price': this.form.jform_base_price,
            };


            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.saveVariants&format=raw", {
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
                this.variants_loading = false;
                this.setSavedClass = true;
                setTimeout(() => {
                    this.setSavedClass = false;
                }, 3000)
                return await this.refreshVariants();
            }


        },
        async saveVariantValues() {

            this.variants_loading = true;

            const params = {
                'variantList': this.form.jform_variantList
            };


            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.updateVariantValues&format=raw", {
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
                this.variants_loading = false;
                this.setSavedClass = true;
                setTimeout(() => {
                    this.setSavedClass = false;
                }, 3000)
                return await this.refreshVariants();
            }


        },
        async refreshVariants() {

            const params = {
                'j_item_id': this.form.itemid
            };


            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.refreshVariants&format=raw", {
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
                this.form.jform_variantList = response.data.variantList;
                this.form.jform_variants = response.data.variants;
                return true;
            }
        },
        setVariantDefault(itemIndex) {

            this.form.jform_variantList.forEach((variant, index) => {
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
            this.form.jform_variantList.forEach((variant, index) => {
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
        async addLabel(e, variant_id) {


            // get the array of current labels
            let loc_array = e.value;

            console.log(loc_array);

            // get the last entered label
            let enteredValue = loc_array[loc_array.length - 1];

            // chop off the last label, since it only contains the entered text
            loc_array.splice(-1);

            // now push a new object into the array with the id as zero etc.
            loc_array.push({
                id: 0,
                name: enteredValue,
                product_id: this.form.itemid,
                variant_id: variant_id
            });


        },
        async onAddNewLabel(e, variant_id) {
            await this.addLabel(e, variant_id);
            await this.setVariants();
            await this.saveItem();
        },
        async removeLabel(event, index, variant_id) {

            this.form.jform_variants[index].labels.push(event.value[0]);
            await UIkit.modal.confirm(this.COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE);
            this.form.jform_variants[index].labels.splice(-1);
            await this.setVariants();
            await this.saveItem();
        },


        /**
         * CHECKBOX OPTIONS
         */

        addOption() {

            this.form.jform_options.push({
                id: 0,
                option_name: '',
                modifier_type: 'amount',
                modifier_value: 0,
                delete: false
            })
        },
        removeOption(option) {
            option.delete = true;
        },

        /**
         * FILE EDIT
         */

        openFileEdit(file) {
            this.file_for_edit = file;
            this.openAddFile();
        },
        openAddFile() {
            UIkit.modal("#fileEditModal").show();
        },
        removeFile() {
            this.file_for_edit.filename_obscured = false;
        },
        fileUploaded(data) {
            this.file_for_edit.filename_obscured = data.path;
            this.file_for_edit.filename = data.filename;
        },
        async saveFile() {

            const params = {
                'fileid': this.file_for_edit.id,
                'created': this.file_for_edit.created,
                'download_access': this.file_for_edit.download_access,
                'filename': this.file_for_edit.filename,
                'filename_obscured': this.file_for_edit.filename_obscured,
                'isjoomla': (this.file_for_edit.isjoomla ? 1 : 0),
                'php_min': this.file_for_edit.php_min,
                'published': (this.file_for_edit.published ? 1 : 0),
                'stability_level': this.file_for_edit.stability_level,
                'type': this.file_for_edit.type,
                'version': this.file_for_edit.version,
                'product_id': this.product_id
            };


            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=file.save&format=raw", {
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
                    message: this.COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED,
                    status: 'success',
                    pos: 'top-center',
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
        cancelFile() {
            this.file_for_edit = {};
        },


        /**
         * SAVE AND UTILITIES
         */


        async saveItem() {

            this.form.jform_long_description = this.getFrameContents('jform_long_description');
            this.form.jform_short_description = this.getFrameContents('jform_short_description');
            this.form.jform_publish_up_date = document.getElementById("jform_publish_up_date").value;
            this.form.jform_access = document.getElementById("jform_access").value;

            const params = {
                'itemid': this.form.itemid,
                'title': this.form.jform_title,
                'introtext': this.form.jform_short_description,
                'fulltext': this.form.jform_long_description,
                'category': this.form.jform_catid,
                'access': this.form.jform_access,
                'base_price': this.form.jform_base_price,
                'discount': this.form.jform_discount,
                'discount_type': this.form.jform_discount_type,
                'tags': this.form.jform_tags,
                'sku': this.form.jform_sku,
                'stock': this.form.jform_stock,
                'manage_stock': (this.form.jform_manage_stock ? 1 : 0),
                'featured': (this.form.jform_featured ? 1 : 0),
                'state': (this.form.jform_state ? 1 : 0),
                'taxable': (this.form.jform_taxable ? 1 : 0),
                'teaserimage': this.form.jform_teaserimage,
                'fullimage': this.form.jform_fullimage,
                'shipping_mode': this.form.jform_shipping_mode,
                'flatfee': this.form.jform_flatfee,
                'publish_up_date': this.form.jform_publish_up_date,
                'product_type': this.form.jform_product_type,
                'options': this.form.jform_options,
                'variants': this.form.jform_variants,
                'variantList': this.form.jform_variantList,
                'custom_fields': this.custom_fields
            };

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.save&format=raw", {
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

                this.form.jform_options = response.data.options;

                UIkit.notification({
                    message: this.COM_PROTOSTORE_ADD_PRODUCT_ALERT_SAVED,
                    status: 'success',
                    pos: 'bottom-right',
                    timeout: 5000
                });


                if (this.andClose) {
                    // if 'andClose' is true, redirect back to the list page
                    window.location.href = this.base_url + 'index.php?option=com_protostore&view=products';
                } else {
                    // if 'andClose' is still false, the user wants to stay on the page.
                    // this line makes sure that a new item gets the ID appended to the URL
                    const url = window.location.href;
                    if (url.indexOf('&id=') == -1) {
                        history.replaceState('', '', url + '&id=' + response.data.joomlaItem.id);
                    }

                    // we also need to make sure that the next save action doesn't trigger a create... we do this by adding the id to the form array
                    this.form.itemid = response.data.joomlaItem.id;

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
        getSellPrice() {

            // const options = {
            //     maximumFractionDigits: 2,
            //     currency: this.p2s_currency.iso,
            //     style: "currency",
            //     currencyDisplay: "symbol"
            // }
            //
            //
            // if (this.discount_type == 1) {
            //
            //     this.sellPrice = this.localStringToNumber(this.form.jform_base_price - this.form.jform_discount).toLocaleString(undefined, options);
            // } else {
            //
            //     // work out the percentage
            //     const discount = (this.form.jform_base_price / 100) * this.form.jform_discount;
            //
            //     this.sellPrice = this.localStringToNumber(this.form.jform_base_price - discount).toLocaleString(undefined, options);
            // }
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
                        if (theInput.innerText == 'null') {
                            this.form[jfrom] = [];
                        }
                        if (theInput.id === 'jform_base_price_data' || theInput.id === 'jform_discount_data' || theInput.id === 'jform_flatfee_data') {
                            this.form[jfrom] = (Number(theInput.innerText) / 100);
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
        },

        /**
         * MEDIA MANAGER
         */
        async getFolderTree() {

            this.mediaLoading = true;
            this.selected_images = [];
            this.selected_folders = [];

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=mediaManager.getFolderTree&format=raw", {
                method: 'POST',
                mode: 'cors',
                cache: 'no-cache',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                redirect: 'follow',
                referrerPolicy: 'no-referrer',
                body: ''
            });


            const response = await request.json();

            if (response.success) {
                this.mediaLoading = false;
                this.selected_images = [];
                this.folderTree = response.data;
            }

        },
        async openBreadcrumb(folder, index) {
            this.selected_images = [];
            this.selected_folders = [];
            this.breadcrumbs.splice((index + 1), this.breadcrumbs.length);
            this.currentParent = folder.id;
        },
        async openFolder(folder) {
            this.selected_images = [];
            this.selected_folders = [];
            this.breadcrumbs.push(folder);
            this.currentParent = folder.id;
        },
        async setToHome() {
            this.selected_images = [];
            this.selected_folders = [];
            this.breadcrumbs = [];
            this.currentParent = 0;
        },
        toggleSelectImage(image) {

            const index = this.selected_images.indexOf(image);

            if (index > -1) {
                this.selected_images.splice(index, 1);

            } else {
                this.selected_images.push(image);
            }


        },
        selectFile(image) {
            this.selected_images.push(image);
        },
        selectImage(id) {
            const keys = Object.keys(this.form);
            keys.forEach((jfrom) => {
                if (jfrom == id) {
                    this.form[jfrom] = this.selected_images[0].relname;
                }
            });

            const custom_field = this.custom_fields.find(custom_field => custom_field.id === id);

            if (custom_field) {
                custom_field.value = this.selected_images[0].relname;
            }


            UIkit.modal('#mediaField' + id).hide();
        },
        async editFileName() {

            const name = await UIkit.modal.prompt(this.COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT, this.selected_images[0].name, {stack: true})
            if (name) {

                const params = {
                    image: this.selected_images[0],
                    new_name: name
                }

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=mediaManager.editName&format=raw", {
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

                    await this.getFolderTree();

                }

            }

        },
        async editFolderName() {

            const name = await UIkit.modal.prompt(this.COM_PROTOSTORE_MEDIA_MANAGER_EDIT_NAME_PROMPT, this.selected_folders[0].name, {stack: true})
            if (name) {

                const params = {
                    folder: this.selected_folders[0],
                    new_name: name
                }

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=mediaManager.editFolderName&format=raw", {
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
                    this.selected_images = [];
                    this.selected_folders = [];
                    await this.getFolderTree();

                }

            }

        },
        async trashSelected() {

            await UIkit.modal.confirm(this.COM_PROTOSTORE_MEDIA_MANAGER_DELETE_ARE_YOU_SURE, {stack: true})

            const params = {
                folders: this.selected_folders,
                images: this.selected_images
            }

            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=mediaManager.trashSelected&format=raw", {
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
                await this.getFolderTree();

            }

        },
        async addFolder() {

            const name = await UIkit.modal.prompt(this.COM_PROTOSTORE_MEDIA_MANAGER_FOLDER_ADD_FOLDER_PROMPT, '', {stack: true})
            if (name) {


                const params = {name, currentDirectory: this.currentDirectory}

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=mediaManager.addFolder&format=raw", {
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
                    await this.getFolderTree();

                }

            }

        },
        async uploadImage(e) {
            this.mediaLoading = true;

            let files = e.target.files;

            [...files].forEach((file) => {

                let formData = new FormData();
                formData.append("image", file, file.name);

                fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=mediaManager.uploadImage&format=raw&directory=" + this.currentDirectory.fullname, {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    reportProgress: true,
                    observe: 'events',
                    body: formData
                }).then((response) => {
                    if (response.success) {

                    }
                });

            });

            UIkit.notification({
                message: this.COM_PROTOSTORE_MEDIA_MANAGER_UPLOADED_MODAL,
                status: 'success',
                pos: 'bottom-right',
                timeout: 5000
            });

            await this.getFolderTree();

        }

    },
    components: {
        'p-inputswitch':
        primevue.inputswitch,
        'p-chips':
        primevue.chips,
        'p-chip':
        primevue.chip,
        'p-inputnumber':
        primevue.inputnumber,
        'p-multiselect':
        primevue.multiselect
    }
}
Vue.createApp(p2s_product_form).mount('#p2s_product_form');

