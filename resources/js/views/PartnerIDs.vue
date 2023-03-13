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
    name: 'PartnerIDs',
    components: {
        Table,
    },
    mixins:[common, pagination],
    data() {
        return {
            entity: {
                label: 'Partner IDs',
                route: 'partner-ids',
                withIndex: true,
                fillables:[
                    {
                        label: 'Partner',
                        raw: data => data.subpartner.partner.name,
                        data: 'raw',
                    },
                    {
                        label: 'Lot number',
                        raw: data => data.number.lotNumber,
                        data: 'raw',
                    },
                    {
                        label: 'Procurement number',
                        raw: data => data.number.procurementNumber,
                        data: 'raw',
                    },
                    {
                        label: 'Subpartner',
                        raw: data => data.subpartner.name,
                        data: 'raw',
                    },
                    {
                        label: 'ID',
                        raw: (data) => {
                            return data.created_at.replace(/-/g, '').substring(2,8)
                                +'-'+this.iD(data.subpartner.partner_id,this.pPad)
                                +'-'+this.iD(data.subpartner_id,this.sPad)
                                +'-'+this.iD(data.id,this.pad);
                        },
                        data: 'raw',
                        class: (data) => {
                            const td = new Date();
                            const pd = new Date(new Date().setDate(td.getDate() - 30));
                            const dd = new Date(data.created_at.substring(0,10));
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
