<template>
  
</template>

<script>
import common from '@/mixins/common';
import Form from '../components/Form.vue';
export default {
  components: {
    Form,
  },
  mixins: [common],
  data() {
    return {
      entity: {
        name: 'partners',
        fillables: [
          {
            codename: 'email',
            type: 'email',
            title: 'Email',
            required: true,
            // eslint-disable-next-line no-useless-escape
            regex: '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$',
            validationMessage: 'Invalid email address',
          },
          {
            codename: 'password',
            type: 'password',
            title: 'Password',
            required: true,
            regex: '.{8,}',
            validationMessage: 'Password must has length of 8',
          },
        ],
        // fillables here
        email: null, // must match the codename
        password: null,
        // fillables end
      },
      submit: {
        type: 'POST',
        link: `/api/${this.entity.name}/`,
        buttonName: 'Login',
      },
      loading: false,
      alert: {
        type: null,
        message: null,
      },
      data: null,
    };
  },
  methods: {
    async onSubmit(entity) {
      this.alert.type = null;
      this.alert.message = null;
      if (this.validated(entity)) {
        this.loading = true;
        axios.get('/sanctum/csrf-cookie').then(r => {
          axios({
            method: this.submit.type,
            url: this.submit.link,
            data: this.data,
            withCredentials: true,
          })
          .then(response => {
            // console.log('response', typeof response.status);
            if(response.status === 200) {
              this.alert.type = 'alert-success';
              this.alert.message = 'Logged in successfully, proceeding...';
              this.authenticate(response.data.data);
              // await nextTick();
              this.goTo("/partners");
            } else {
              this.alert.type = 'alert-danger';
              this.alert.message = 'Invalid credentials, try again';
            }
          })
          .catch((error) => {
            console.log('error', error);
            this.alert.type = 'alert-danger';
            this.alert.message = 'Invalid credentials, try again';
          })
          .then(_ => {
            this.loading = false;
          });
        });
      }
    },
    validated(entity) {
      let r = true;
      this.data = {};
      for (let i = 0; i < entity.fillables.length; i++) {
        entity.fillables[i].error = null;
        this.data[entity.fillables[i].codename] = entity[entity.fillables[i].codename];
        if (
          entity.fillables[i].hasOwnProperty('required')
          && entity.fillables[i].required === true
          && entity[entity.fillables[i].codename] === null) {
          entity.fillables[i].error = "This field is required";
          r = false;
        }
        const regex = new RegExp(entity.fillables[i].regex);
        if (!regex.test(entity[entity.fillables[i].codename])) {
          entity.fillables[i].error = entity.fillables[i].validationMessage;
          r = false;
        }
      }
      return r;
    },
  },
  created() {
    if( this.$route.params.hasOwnProperty('data') ) {
      this.currentPage = this.$route.params.page;
    }
    this.getPage(this.currentPage);
  },
};
</script>