////////////////////////////////////////////////////////////////////////////////
// @package   Pro2Store
// @author    Ray Lawlor - pro2.store
// @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
// @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
//

const p2s_presetvariants = {
    data() {
        return {
            base_url: '',
            p2s_currency: '',
            p2s_local: '',
            items: [],
            itemsChunked: [],
            categories: [],
            selectedCategory: 0,
            currentSort: 'title',
            currentSortDir: 'asc',
            currentPage: 0,
            pages: [],
            pagesizes: [5, 10, 15, 20, 25, 30, 50, 100, 200, 500],
            show: 25,
            selected: [],
            confirm_LangString: '',
            changeCategory: 0,
            showChangeCat: false,
            showChangeStock: false,
            changeStock: 0,
            editStock: false
        };
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


        const items_data = document.getElementById('items_data');
        this.items = JSON.parse(items_data.innerText);
        // items_data.remove();

        const categories_data = document.getElementById('categories_data');
        this.categories = JSON.parse(categories_data.innerText);
        categories_data.remove();

        const show = document.getElementById('page_size');
        this.show = show.innerText;
        show.remove();

        const confirmLangString = document.getElementById('confirmLangString');
        try {
            this.confirm_LangString = confirmLangString.innerText;
            confirmLangString.remove();
        } catch (err) {
        }

    },
    mounted: function () {
        this.changeShow();
    },
    computed: {},
    methods: {
        async updateList() {
            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.updatelist&format=raw&limit=0", {
                method: 'post'
            });

            const response = await request.json();

            if (response.success) {


            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }
        },
        async filter() {

            this.loading = true;

            const params = {
                'limit': this.show,
                'offset': (this.currentPage * this.show),
                'category': this.selectedCategory,
                'searchTerm': (this.enteredText ? this.enteredText.trim() : ''),

            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.filter&format=raw&' + URLparams, {method: 'post'});

            const response = await request.json();

            if (response.success) {
                this.items = response.data.items;
                this.loading = false;

                if (this.items) {
                    this.changeShow();
                } else {
                    this.itemsChunked = [];
                    this.pages = 1;
                    this.currentPage = 0;
                }
            }

        },
        changeShow() {

            this.itemsChunked = this.items.reduce((resultArray, item, index) => {
                const chunkIndex = Math.floor(index / this.show)
                if (!resultArray[chunkIndex]) {
                    resultArray[chunkIndex] = []
                }
                resultArray[chunkIndex].push(item)
                return resultArray
            }, []);
            this.pages = this.itemsChunked.length;
            this.currentPage = 0;
        },
        changePage(i) {
            this.currentPage = i;
        },
        async doTextSearch(event) {
            this.enteredText = null
            clearTimeout(this.debounce)
            this.debounce = setTimeout(() => {
                this.enteredText = event.target.value
                this.filter();
            }, 600)
        },
        sort(s) {
            //if s == current sort, reverse
            if (s === this.currentSort) {
                this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
            }
            this.currentSort = s;
            return this.itemsChunked[this.currentPage].sort((a, b) => {
                let modifier = 1;
                if (this.currentSortDir === 'desc') modifier = -1;
                if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                return 0;
            });
        },
        async toggleSelected() {

            const params = {
                'items': this.selected
            };

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.togglePublished&format=raw',
                {
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
                this.selected = [];
                await this.filter();

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }

        },
        async togglePublished(product) {

            this.selected = [];
            this.selected.push(product);
            await this.toggleSelected();
        },
        async trashSelected() {

            await UIkit.modal.confirm(this.confirm_LangString);

            const params = {
                'items': this.selected
            };


            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.trash&format=raw", {
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
                this.selected = [];
                await this.filter();
            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }


        },
        openChangeCategory() {
            this.showChangeCat = true;
        },
        openChangeStock() {
            this.showChangeStock = true;
        },
        async runChangeCategory() {

            if (this.selected.length > 0) {
                const params = {
                    'items': this.selected,
                    'category_id': this.changeCategory
                };

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.changeCategory&format=raw", {
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
                    this.showChangeCat = false;
                    UIkit.notification({
                        message: 'Done',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 5000
                    });
                    this.selected = [];
                    await this.filter();
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
        async runChangeStock() {

            if (this.selected.length > 0) {


                const params = {
                    'items': this.selected,
                    'stock': this.changeStock
                };

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.changeStock&format=raw", {
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
                    this.showChangeStock = false;
                    UIkit.notification({
                        message: 'Done',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 5000
                    });
                    this.selected = [];
                    await this.filter();
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
        openEditStock(product) {
            product.editStock = true;
        },
        openEditPrice(product) {
            product.editPrice = true;
        },
        async saveProductStock(product) {

            delete product.editStock;

            const params = {
                'itemid': product.joomla_item_id,
                'stock': product.stock,
            };

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.saveStock&format=raw',
                {
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
                    pos: 'top-right',
                    timeout: 5000
                });
                await this.filter();

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }

        },
        updateBasePriceFloat(event, product) {
            product.basepriceFloat = event.value;
        },
        async saveProductPrice(product) {
            delete product.editPrice;

            const params = {
                'itemid': product.joomla_item_id,
                'base_priceFloat': product.basepriceFloat,
            };

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.savePrice&format=raw',
                {
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
                    pos: 'top-right',
                    timeout: 5000
                });
                await this.filter();

            } else {
                UIkit.notification({
                    message: 'There was an error.',
                    status: 'danger',
                    pos: 'top-center',
                    timeout: 5000
                });
            }

        },
        selectAll(e) {
            if (e.target.checked) {
                this.selected = this.itemsChunked[this.currentPage];
            } else {
                this.selected = [];
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


    },
    components: {
        'p-inputnumber': primevue.inputnumber,
    }
}

Vue.createApp(p2s_presetvariants).mount('#p2s_presetvariants')
