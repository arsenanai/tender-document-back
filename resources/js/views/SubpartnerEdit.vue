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
import Form from '@/components/Form.vue';
import common from '@/mixins/common';
import forms from '@/mixins/forms';

export default {
  name: 'SubpartnerEdit',
  components: {
    Form,
  },
  mixins: [common, forms],
  data() {
    return {
      entity: {
        label: 'Subpartner Edit Form',
        route: 'subpartners',
        pad: parseInt(import.meta.env.VITE_PAD_PARTNER_ID),
        fillables: [
          {
            codename: 'id',
            type: 'hidden',
            required: true,
          },
          {
            codename: 'name',
            type: 'text',
            title: 'Name',
            required: true,
            validationMessage: 'This field is required',
          },
          {
            codename: 'partner_id',
            type: 'autocomplete',
            autocomplete: {
              for: 'partner',
              selectionField: 'id',
              displayField: 'name',
              minChars: 3,
              link: '/api/partners',
              method: 'GET',
            },
            title: 'Partner',
            required: true,
            validationMessage: 'This field is required',
          },
        ],
        // fillables here
        name: null, // must match the codename
        partner_id: null,
        partner: {
          name: null,
        }
        // fillables end
      },
      submit: 'Update',
      loading: false,
      alert: {
        type: null,
        message: null,
      },
      data: null,
    };
  },
  methods: {
    onSubmit() {
      this.alert.type = null;
      this.alert.message = null;
      if (this.validated(this.entity)) {
        this.loading = true;
        axios({
          method: 'PUT',
          url: `/api/${this.entity.route}/${this.data.id}`,
          data: this.data,
          withCredentials: true,
          headers: {
            'Authorization': `Bearer ${this.getUserToken()}`,
          }
        })
        .then(response => {
          console.log('response', response);
          if(response.status === 202 && response.data.success === true) {
            this.alert.type = 'alert-success';
            this.alert.message = `Updation successful`;
          } else {
            this.alert.type = 'alert-danger';
            this.alert.message = `Updation failed`;
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
      }
    },
  },
  created() {
    this.populateData(this.$route, this.entity, this.data);
  },
}
</script>