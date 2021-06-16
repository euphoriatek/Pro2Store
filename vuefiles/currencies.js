const p2s_currencies = {
    data() {
        return {
            base_url: '',
            currencies: [],
            currenciesChunked: [],
            currentSort: 'name',
            currentSortDir: 'asc',
            currentPage: 0,
            pages: [],
            pagesizes: [5, 10, 20, 30, 50, 100],
            show: 25,
        };
    },
    async beforeMount() {

        const base_url = document.getElementById('base_url');
        this.base_url = base_url.innerText;
        base_url.remove();

        const currencies_data = document.getElementById('currencies_data');
        this.currencies = JSON.parse(currencies_data.innerText);
        currencies_data.remove();


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
                'searchTerm': this.enteredText,
            };

            const URLparams = this.serialize(params);

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=currencies.filter&format=raw&' + URLparams, {method: 'post'});

            const response = await request.json();

            if (response.success) {
                this.currencies = response.data.currencies;
                this.loading = false;

                if (this.currencies) {
                    this.changeShow();
                } else {
                    this.currenciesChunked = [];
                    this.pages = 1;
                    this.currentPage = 0;
                }
            }

        },
        changeShow() {

            this.currenciesChunked = this.currencies.reduce((resultArray, item, index) => {
                const chunkIndex = Math.floor(index / this.show)
                if (!resultArray[chunkIndex]) {
                    resultArray[chunkIndex] = []
                }
                resultArray[chunkIndex].push(item)
                return resultArray
            }, []);
            this.pages = this.currenciesChunked.length;
            this.currentPage = 0;
            console.log(this.currenciesChunked);
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
            return this.currenciesChunked[this.currentPage].sort((a, b) => {
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

    }
}

Vue.createApp(p2s_currencies).mount('#p2s_currencies')
