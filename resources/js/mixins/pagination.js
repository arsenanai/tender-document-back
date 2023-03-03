export default {
  data() {
    return {
      currentPage: 1,
      loading: false,
    };
  },
  watch:{
    '$route': 'fetchPage'
  },
  mounted() {
    this.fetchPage();
  },
  methods: {
    newEntity() {
        this.goTo(`/${this.entity.route}/create`);
    },
    onNext() {
        this.currentPage = (this.currentPage >= this.entity.page.last_page) ? this.entity.page.last_page-1 : this.currentPage;
        this.goTo({path:`/${this.entity.route}`, query:{page: this.currentPage+=1}});
    },
    onPrev() {
        this.currentPage = (this.currentPage <= 0) ? 2 : this.currentPage;
        this.goTo({path:`/${this.entity.route}`, query:{page: this.currentPage-=1}});
    },
    onEdit(data) {
        localStorage.setItem(`${this.entity.route}-to-edit`, JSON.stringify(data));
        this.goTo({path:`/${this.entity.route}/edit/${data.id}`});
    },
    onDelete(data) {
        this.sendDeleteRequest(data);
    },
    sendDeleteRequest(data) {
        axios({
            method: 'DELETE',
            url: `/api/${this.entity.route}/${data.id}`,
            withCredentials: true,
            headers: {
                'Authorization': `Bearer ${this.getUserToken()}`,
            }
        })
        .then((response) => {
            this.fetchPage();
        }).catch(error => {
            console.log(error)
        }).then(_ => {
            this.loading = false
        })
    },
    fetchPage() {
        this.loading = true;
        axios({
            method: 'GET',
            url: `/api/${this.entity.route}`,
            params: {
                page: this.$route.query.page
            },
            withCredentials: true,
            headers: {
                'Authorization': `Bearer ${this.getUserToken()}`,
            }
        })
        .then((response) => {
            //console.log(response.data);
            this.entity.page = response.data;
            this.currentPage = response.data.current_page;
        }).catch(error => {
            console.log(error)
        }).then(_ => {
            this.loading = false
        })
    },
  }
}