<template>
    <Table
        :entity="entity"
        :loading="loading"
        @new-entity="newEntity"
        @on-next="onNext"
        @on-prev="onPrev"
        @on-edit="onEdit"
        @on-delete="onDelete"
        />
</template>

<script>
import common from '@/mixins/common';
import pagination from '@/mixins/pagination';
import Table from '../components/Table.vue';
export default {
    name: 'Partner IDs',
    components: {
        Table,
    },
    mixins:[common, pagination],
    data() {
        return {
            entity: {
                label: 'Partner IDs',
                name: 'partner-ids',
                pad: import.meta.env.VITE_PAD_SUBPARTNER_ID,
                fillables:[
                    {
                        label: 'Lot number',
                        name: 'lotNumber',
                        classes: 'col-12 col-md-2',
                        dataClasses: 'py-1 text-truncate',
                    },
                    {
                        label: 'Procurement number',
                        name: 'procurementNumber',
                        classes: 'col-12 col-md-2',
                        dataClasses: 'py-1 text-truncate',
                    },
                    {
                        label: 'Comments',
                        name: 'comments',
                        classes: 'col-12 col-md',
                        dataClasses: 'py-1 text-truncate',
                    },
                ],
                page: null,
            },
        }
    },
}
</script>
