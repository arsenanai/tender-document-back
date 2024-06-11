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
    name: 'Partners',
    components: {
        Table,
    },
    mixins:[common, pagination],
    data() {
        return {
            entity: {
                label: this.$t('Partners'),
                route: 'partners',
                pad: parseInt(import.meta.env.VITE_PAD_PARTNER_ID),
                fillables:[
                    {
                        label: this.$t('Name'),
                        name: 'name',
                        classes: 'col-4 col-sm-6 col-lg-8',
                        dataClasses: 'py-1 text-truncate',
                    },
                    {
                        label: this.$t('Created at'),
                        data: 'raw',
                        raw: data => data.created_at.replaceAll('T', ' ').substring(0,16),
                    },
                    {
                        label: this.$t('BIN'),
                        name: 'bin',
                        classes: 'col-4 col-sm-6 col-lg-8',
                        dataClasses: 'py-1 text-truncate',
                    },
                ],
                page: null,
            },
        }
    },
}
</script>
