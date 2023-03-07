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
  components: {
    Form,
  },
  name: 'PartnerIDEdit',
  mixins: [common, forms],
  data() {
    return {
      entity: {
        label: 'Partner ID Edit Form',
        route: 'partner-ids',
        pad: parseInt(import.meta.env.VITE_PAD_SUBPARTNER_ID),
        fillables: [
          {
            codename: 'id',
            type: 'hidden',
            required: true,
          },
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
            this.alert.type = 'text-success';
            this.alert.message = `Updation successful`;
          } else {
            this.alert.type = 'text-danger';
            this.alert.message = `Updation failed`;
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
  created() {
    this.populateData(this.$route, this.entity, this.data);
  },
}
</script>