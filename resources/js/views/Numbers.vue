<template>
  <Table
      :entity="entity"
      :loading="loading"
      @new-entity="newEntity"
      @on-next="onNext"
      @on-prev="onPrev"
      @on-edit="onEdit"
      @on-delete="onDelete"
      @on-search="onSearch"
      />
</template>

<script>
import common from '@/mixins/common';
import pagination from '@/mixins/pagination';
import Table from '../components/Table.vue';
export default {
  name: 'Numbers',
  components: {
      Table,
  },
  mixins:[common, pagination],
  data() {
      return {
          entity: {
              label: this.$t('Numbers'),
              route: 'numbers',
              pad: parseInt(import.meta.env.VITE_PAD_PARTNER_ID),
              fillables:[
                  {
                      label: this.$t('Partner'),
                      raw: data => data.partner.name,
                      data: 'raw',
                  },
                  {
                      label: this.$t('Lot number'),
                      name: 'lotNumber',
                  },
                  {
                      label: this.$t('Procurement number'),
                      name: 'procurementNumber',
                  },
                  {
                      label: this.$t('Created at'),
                      data: 'raw',
                      raw: data => data.created_at.replaceAll('T', ' ').substring(0,16),
                  }
              ],
              page: null,
          },
      }
  },
}
</script>
