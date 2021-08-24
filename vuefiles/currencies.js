const p2s_currencies = {
    data() {
        return {
            base_url: '',
            items: [],
            itemsChunked: [],
            currentSort: 'name',
            currentSortDir: 'asc',
            currentPage: 0,
            pages: [],
            pagesizes: [5, 10, 15, 20, 25, 30, 50, 100, 200, 500],
            show: 25,
            enteredText: '',
            publishedOnly: true,
        };
    },
    async beforeMount() {

        const base_url = document.getElementById('base_url');
        this.base_url = base_url.innerText;
        base_url.remove();

        const items_data = document.getElementById('items_data');
        try {
            this.items = JSON.parse(items_data.innerText);
            items_data.remove();
        } catch (err) {
        }

        const show = document.getElementById('page_size');
        this.show = show.innerText;
        show.remove();


    },
    mounted: function () {
        this.changeShow();
    },
    computed: {},
    methods: {

        async updateList() {
            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=currencies.updatelist&format=raw&limit=0", {
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
               'searchTerm': (this.enteredText ? this.enteredText.trim() : ''),

                'publishedOnly': this.publishedOnly,
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=currencies.filter&format=raw&' + URLparams, {method: 'post'});

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
        cleartext(){
            this.enteredText = null;
            this.doTextSearch();
        },
        async doTextSearch(event) {
            clearTimeout(this.debounce)
            this.debounce = setTimeout(() => {
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
        async togglePublished(currency) {

            const params = {
                'currency_id': currency.id
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=currency.togglePublished&format=raw&' + URLparams, {method: 'post'});

            const response = await request.json();

            if (response.success) {
                currency.published = response.data
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
    }
}

Vue.createApp(p2s_currencies).mount('#p2s_currencies')
