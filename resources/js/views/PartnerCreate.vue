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
  name: 'PartnerCreate',
  components: {
    Form,
  },
  mixins: [common, forms],
  data() {
    return {
      entity: {
        label: 'Partner Create Form',
        route: 'partners',
        fillables: [
          {
            codename: 'name',
            type: 'text',
            title: 'Name',
            required: true,
            validationMessage: 'This field is required',
          },
        ],
        // fillables here
        name: null, // must match the codename
        // fillables end
      },
      submit: 'Create',
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
          method: 'POST',
          url: `/api/${this.entity.route}`,
          data: this.data,
          withCredentials: true,
          headers: {
            'Authorization': `Bearer ${this.getUserToken()}`,
          }
        })
        .then(response => {
          console.log('response', response);
          if(response.status === 201 && response.data.success === true) {
            this.alert.type = 'text-success';
            this.alert.message = `Creation successful`;
          } else {
            this.alert.type = 'text-danger';
            this.alert.message = `Creation failed`;
          }
        })
        .catch((error) => {
          console.log('error', error);
          this.alert.type = 'text-danger';
          this.alert.message = 'Server side error, contact vendor';
        })
        .then(_ => {
          this.loading = false;
        });
      }
    },
  },
}
</script>