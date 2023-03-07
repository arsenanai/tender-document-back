<template>
  <MyForm
    :entity="entity"
    :submit="submit"
    :alert="alert"
    :loading="loading"
    @onSubmit="onSubmit"
  />
</template>

<script>
import MyForm from '../components/Form.vue';
import common from '@/mixins/common';
import forms from '@/mixins/forms';
// import router from '../router/index';
export default {
  components: {
    MyForm,
  },
  mixins: [common, forms],
  data() {
    return {
      entity: {
        label: 'Login Form',
        fillables: [
          {
            codename: 'email',
            type: 'email',
            title: 'Email',
            required: true,
            // eslint-disable-next-line no-useless-escape
            regex: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/,
            validationMessage: 'Invalid email address',
          },
          {
            codename: 'password',
            type: 'password',
            title: 'Password',
            required: true,
            regex: /^.{8,}/,
            validationMessage: 'Password must have a length of 8',
          },
        ],
        // fillables here
        email: null, // must match the codename
        password: null,
        // fillables end
      },
      submit: 'Login',
      loading: false,
      alert: {
        type: null,
        message: null,
      },
    };
  },
  methods: {
    onSubmit() {
      this.alert.type = null;
      this.alert.message = null;
      // from forms mixin
      if (this.validated(this.entity)) {
        this.loading = true;
        axios.get('/sanctum/csrf-cookie').then(r => {
          axios({
            method: 'POST',
            url: '/api/login',
            // from forms mixin
            data: this.data,
            withCredentials: true,
          })
          .then(response => {
            // console.log('response', typeof response.status);
            if(response.status === 200) {
              this.alert.type = 'text-success';
              this.alert.message = 'Logged in successfully, proceeding...';
              this.authenticate(response.data.data);
              // await nextTick();
              this.goTo("/partners");
            } else {
              this.alert.type = 'text-danger';
              this.alert.message = 'Invalid credentials, try again';
            }
          })
          .catch((error) => {
            console.log('error', error);
            this.alert.type = 'text-danger';
            this.alert.message = 'Invalid credentials, try again';
          })
          .then(_ => {
            this.loading = false;
          });
        });
      }
    },
  },
  created() {
    if (this.authenticated()) {
      this.goTo('/partners');
    }
  },
}
</script>