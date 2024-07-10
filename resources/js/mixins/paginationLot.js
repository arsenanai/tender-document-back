export default {
    data() {
        return {
            currentPage: 1,
            loading: false,
        };
    },
    watch: {
        $route: "fetchPage",
    },
    mounted() {
        this.fetchPage();
    },
    methods: {
        newEntity() {
            this.goTo(`/${this.entity.route}/create`);
        },
        onNext() {
            const lastPage = this.entity.page.last_page;         
            this.currentPage = this.entity.page.current_page;
            if(this.currentPage < lastPage) {
                this.goTo(this.entity.page.next_page_url);
            }
        },
        onPrev() {
            this.currentPage = this.currentPage <= 1 ? 2 : this.currentPage;
            this.goTo({
                path: this.entity.route,
                query: { page: (this.currentPage -= 1) },
                search: this.entity.page.search,
            });
        },
        onEdit(data) {
            localStorage.setItem(
                `${this.entity.route}-to-edit`,
                JSON.stringify(data)
            );
            this.goTo({ path: `/${this.entity.route}/edit/${data.id}` });
        },
        onShow(data) {
            localStorage.setItem(
                `${this.entity.route}-to-show`,
                JSON.stringify(data)
            );
            this.goTo({ path: `/${this.entity.route}/show/${data.id}` });
        },
        onSearch(input) {
            this.currentPage = 1;
            this.goTo({
                path: `/${this.entity.route}`,
                query: { search: input },
            });
        },
        onDelete(data) {
            this.sendDeleteRequest(data);
        },
        sendDeleteRequest(data) {
            axios({
                method: "DELETE",
                url: `/api/${this.entity.route}/${data.id}`,
                withCredentials: true,
                headers: {
                    Authorization: `Bearer ${this.getUserToken()}`,
                },
            })
                .then((response) => {
                    this.fetchPage();
                })
                .catch((error) => {
                    console.log(error);
                    if (error.response.status === 401) {
                        this.eraseUserData();
                        this.goTo("/login");
                    }
                })
                .then((_) => {
                    this.loading = false;
                });
        },

        onCloselot(data) {
            this.sendCloseRequest(data);
        },
        sendCloseRequest(data) {
            let lotNumb = data.result.lotNumber;
            // console.log(data.result.lotNumber);
            axios({
                method: "GET",
                url: `${this.url}/announcement/close-lot/${lotNumb}`,
                withCredentials: true,
                headers: {
                    Authorization: `Bearer ${this.getUserToken()}`,
                },
            })
                .then((response) => {
                    this.fetchPage();
                })
                .catch((error) => {
                    console.log(error);
                    if (error.response.status === 401) {
                        this.eraseUserData();
                        this.goTo("/login");
                    }
                })
                .then((_) => {
                    this.loading = false;
                });
        },

        fetchPage() {
            this.loading = true;
            console.log("this.$route.query.page", this.$route.query.page);
            console.log("this.$route.query.search", this.$route.query.search);

            axios({
                method: "GET",
                url: `${this.url}/announcement/verify-results`,
                params: {
                    page: this.$route.query.page,
                    search: this.$route.query.search,
                },
                withCredentials: true,
                headers: {
                    Authorization: `Bearer ${this.getUserToken()}`,
                },
            })
                .then((response) => {
                    // console.log(response.data.data[0].result);
                    // console.log("from loto", response.data.current_page);
                    this.entity.page = response.data;
                    this.currentPage = response.data.current_page;
                })
                .catch((error) => {
                    console.log(error);
                    if (error.response.status === 401) {
                        this.eraseUserData();
                        this.goTo("/login");
                    }
                })
                .then((_) => {
                    this.loading = false;
                });
        },
    },
};
