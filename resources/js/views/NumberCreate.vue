<template>
  <Form
    :entity="entity"
    :submit="submit"
    :alert="alert"
    :loading="loading"
    @onSubmit="onSubmit"
    @onKeyup="onKeyup"
  />
</template>

<script>
import Form from '@/components/Form.vue';
import common from '@/mixins/common';
import forms from '@/mixins/forms';
import prevalidation from '@/mixins/prevalidation';

export default {
  name: 'NumberCreate',
  components: {
    Form,
  },
  mixins: [common, forms, prevalidation],
  data() {
    return {
      entity: {
        label: 'Number Create Form',
        route: 'numbers',
        fillables: [
          {
            codename: 'partner_id',
            type: 'autocomplete',
            autocomplete: {
              for: 'partner',
              selectionField: 'id',
              displayField: 'name',
              minChars: 8,
              link: '/api/partners',
              method: 'GET',
            },
            title: 'Partner',
            required: true,
            validationMessage: 'This field is required',
          },
          {
            codename: 'lotNumber',
            type: 'text',
            title: 'Lot Number',
            required: true,
            validationMessage: 'This field is required',
            preValidation: {
              link: '/api/numbers',
              method: 'GET',
              minChars: 3,
              message: (data, input, fillable) => {
                if (data.length > 1) {
                  fillable.feedbackMessage = `${this.$t('Already in use in')} <a target="_blank"
                  href="/numbers?search=${input}&filterBy=lotNumber">${data.length} ${this.$t('numbers')}</a>`;
                  fillable.hasError = true;
                } else if (data.length === 1) {
                  fillable.feedbackMessage = `${this.$t('Already in use in')} <a target="_blank"
                  href="/numbers/edit/${data[0].id}">${data[0].partner.name}</a>`;
                  fillable.hasError = true;
                }
              },
            }
          },
          {
            codename: 'procurementNumber',
            type: 'text',
            title: 'Procurement Number',
            required: true,
            validationMessage: 'This field is required',
            preValidation: {
              link: '/api/numbers',
              method: 'GET',
              minChars: 3,
              message: (data, input, fillable) => {
                if (data.length > 1) {
                  fillable.feedbackMessage = `${this.$t('Already in use by')} <a target="_blank"
                  href="/numbers?search=${input}&filterBy=procurementNumber">${data.length} ${this.$t('numbers')}</a>`;
                  fillable.hasError = true;
                } else if (data.length === 1) {
                  fillable.feedbackMessage = `${this.$t('Already in use by')} <a target="_blank"
                  href="/numbers/edit/${data[0].id}">${data[0].partner.name}</a>`;
                  fillable.hasError = true;
                }
              },
            }
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
          //console.log('response', response);
          if(response.status === 201 && response.data.success === true) {
            this.alert.type = 'text-success';
            this.alert.message = `Creation successful`;
          } else {
            this.alert.type = 'text-danger';
            this.alert.message = `Creation failed`;
          }
        })
        .catch((error) => {
          this.alert.type = 'text-danger';
          if (error.response.status === 422) {
            for (let ii = 0; ii < this.entity.fillables.length; ii+=1) {
              if (error.response.data.errors.hasOwnProperty(this.entity.fillables[ii].codename)) {
                this.entity.fillables[ii].hasError = true;
                this.entity.fillables[ii].feedbackMessage = error.response.data.errors[
                  this.entity.fillables[ii].codename
                ][0];
              }
            }
            this.alert.message = 'Invalid data provided';
          } else {
            this.alert.message = 'Server side error, contact vendor';
          }
        })
        .then(_ => {
          this.loading = false;
        });
      }
    },
  },
}
</script>