const p2s_productoptions = {
    data() {
        return {
            base_url: '',
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
        };
    },
    async beforeMount() {

        const base_url = document.getElementById('base_url');
        this.base_url = base_url.innerText;
        base_url.remove();

        const items_data = document.getElementById('items_data');
        this.items = JSON.parse(items_data.innerText);
        // items_data.remove();

        const categories_data = document.getElementById('categories_data');
        this.categories = JSON.parse(categories_data.innerText);
        categories_data.remove();

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
            const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=productoptions.updatelist&format=raw&limit=0", {
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

            const request = await fetch(this.base_url + 'index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=productoptions.filter&format=raw&' + URLparams, {method: 'post'});

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

Vue.createApp(p2s_productoptions).mount('#p2s_productoptions')
