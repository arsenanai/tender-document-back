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
                withIndex: true,
                fillables:[
                    {
                        label: 'Lot number',
                        name: 'lotNumber',
                    },
                    {
                        label: 'Procurement number',
                        name: 'procurementNumber',
                    },
                    {
                        label: 'Partner',
                        name: 'partner_name',
                    },
                    {
                        label: 'Subpartner',
                        name: 'subpartner_name',
                    },
                    {
                        label: 'ID',
                        raw: (data) => {
                            return data.date.replace(/-/g, '').substring(2,8)
                                +'-'+this.iD(data.partner_id,this.pPad)
                                +'-'+this.iD(data.subpartner_id,this.sPad)
                                +'-'+this.iD(data.id,this.pad);
                        },
                        data: 'raw',
                        class: (data) => {
                            const td = new Date();
                            const pd = new Date(new Date().setDate(td.getDate() - 30));
                            const dd = new Date(data.date);
                            let r = 'text-success';
                            if (dd < pd) {
                                r = 'text-danger';
                            }
                            return r;
                        },
                    },
                ],
                page: null,
            },
            pad: parseInt(import.meta.env.VITE_PAD_ID),
            pPad: parseInt(import.meta.env.VITE_PAD_PARTNER_ID),
            sPad: parseInt(import.meta.env.VITE_PAD_SUBPARTNER_ID),
        }
    },
    methods: {
        iD(id, pad) {
            return id.toString().padStart(pad, '0');
        },
    },
}
</script>
