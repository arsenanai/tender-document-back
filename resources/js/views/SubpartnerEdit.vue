<template>
  <Form
    :entity="entity"
    :submit="submit"
    :alert="alert"
    :loading="loading"
    @onSubmit="onSubmit('Updation')"
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
        label: this.$t('Subpartner Edit Form'),
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
            title: this.$t('Name'),
            required: true,
            validationMessage: this.$t('This field is required'),
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
            title: this.$t('Partner'),
            required: true,
            validationMessage: this.$t('This field is required'),
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
      submit: this.$t('Update'),
      loading: false,
      alert: {
        type: null,
        message: null,
      },
      data: null,
    };
  },
  created() {
    this.populateData(this.$route, this.entity, this.data);
  },
}
</script>