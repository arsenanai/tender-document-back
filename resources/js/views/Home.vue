<template>
  <Form
    :entity="entity"
    :submit="submit"
    :alert="alert"
    :loading="loading"
    @onSubmit="onSubmit"
  />
</template>

<script>
import forms from '@/mixins/forms';
// import common from '@/mixins/common';
import Form from '../components/Form.vue';
export default {
  name: 'Home',
  components: {
    Form,
  },
  mixins: [forms],
  data() {
    return {
      pad: parseInt(import.meta.env.VITE_PAD_ID),
      pPad: parseInt(import.meta.env.VITE_PAD_PARTNER_ID),
      sPad: parseInt(import.meta.env.VITE_PAD_SUBPARTNER_ID),
      entity: {
        label: 'Entry checking form',
        fillables: [
          {
            codename: 'entry',
            type: 'text',
            title: 'Entry Code',
            required: true,
            regex: null,
            validationMessage: 'Invalid entry code format',
          },
        ],
        // fillables here
        entry: null, // must match the codename
        // fillables end
      },
      submit: 'Check',
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
        //axios.get('/sanctum/csrf-cookie').then(r => {
          axios({
            method: 'POST',
            url: '/api/partner-ids/check',
            // from forms mixin
            data: this.data,
            // withCredentials: true,
          })
          .then(response => {
            console.log('response', response);
            if(response.status === 200 && response.data.answer === 'correct') {
              this.alert.type = 'alert-success';
              this.alert.message = `Entry code is valid
                <br><i>Subpartner</i>: <b>${response.data.details.subpartner.name}</b>`;
            } else {
              this.alert.type = 'alert-danger';
              this.alert.message = 'Entry code is invalid';
            }
          })
          .catch((error) => {
            console.log('error', error);
            this.alert.type = 'alert-danger';
            this.alert.message = 'Server side error, contact vendor';
          })
          .then(_ => {
            this.loading = false;
          });
        //});
      }
    },
  },
  created() {
    this.entity.fillables[0].regex = new RegExp(`^([0-9]{6})?[-]?([0-9]{${this.pPad}})?[-]?([0-9]{${this.sPad}})?[-]?([0-9]{${this.pad}})?$`);
  },
}
</script>