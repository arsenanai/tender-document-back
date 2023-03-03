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
  name: 'PartnerIDCreate',
  components: {
    Form,
  },
  mixins: [common, forms],
  data() {
    return {
      entity: {
        label: 'Partner ID Create Form',
        route: 'partner-ids',
        pad: parseInt(import.meta.env.VITE_PAD_SUBPARTNER_ID),
        fillables: [
          {
            codename: 'lotNumber',
            type: 'text',
            title: 'Lot Number',
            required: true,
            validationMessage: 'This field is required',
          },
          {
            codename: 'procurementNumber',
            type: 'text',
            title: 'Procurement Number',
            required: true,
            validationMessage: 'This field is required',
          },
          {
            codename: 'subpartner_id',
            type: 'autocomplete',
            autocomplete: {
              for: 'subpartner',
              selectionField: 'id',
              displayField: 'name',
              minChars: 3,
              link: '/api/subpartners',
              method: 'GET',
            },
            title: 'Subpartner',
            required: true,
            validationMessage: 'This field is required',
          },
          {
            codename: 'comments',
            type: 'textarea',
            title: 'Comments',
          },
        ],
        // fillables here
        lotNumber: null, // must match the codename
        procurementNumber: null,
        comments: null,
        subpartner_id: null,
        subpartner: {
          name: null,
        }
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
            this.alert.type = 'alert-success';
            this.alert.message = `Creation successful`;
          } else {
            this.alert.type = 'alert-danger';
            this.alert.message = `Creation failed`;
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
}
</script>