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
        >
    </Table>
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
                pad: import.meta.env.VITE_PAD_ID,
                pPad: import.meta.env.VITE_PAD_PARTNER_ID,
                sPad: import.meta.env.VITE_PAD_SUBPARTNER_ID,
                label: this.$t('Partner IDs'),
                route: 'partner-ids',
                //withIndex: true,
                fillables:[
                    {
                        label: this.$t('Partner'),
                        raw: data => data.subpartner.partner.name,
                        data: 'raw',
                    },
                    {
                        label: this.$t('Lot number'),
                        raw: data => data.number.lotNumber,
                        data: 'raw',
                    },
                    {
                        label: this.$t('Procurement number'),
                        raw: data => data.number.procurementNumber,
                        data: 'raw',
                    },
                    {
                        label: this.$t('Subpartner'),
                        raw: data => data.subpartner.name,
                        data: 'raw',
                    },
                    {
                        label: this.$t('Full ID'),
                        raw: (data) => {
                            const fullId = data.created_at.replace(/-/g, '').substring(2,8)
                                +'-'+this.iD(data.subpartner.partner_id,this.entity.pPad)
                                +'-'+this.iD(data.subpartner_id,this.entity.sPad)
                                +'-'+this.iD(data.id,this.entity.pad);
                            return `<a href="/generate-qr-code/${fullId}" target='_blank'
                            title="${this.$t('Click to generate QR Code')}"
                            style="color:inherit;text-decoration:inherit"
                            >${fullId}</a>`;
                        },
                        data: 'raw',
                        class: (data) => {
                            const td = new Date();
                            const pd = new Date(new Date().setDate(td.getDate() - 30));
                            const dd = new Date(data.created_at.substring(0,10));
                            let r = 'font-monospace text-success';
                            if (dd < pd) {
                                r = 'font-monospace text-danger';
                            }
                            return r;
                        },
                    },
                ],
                page: null,
            },
        }
    },
    methods: {
        iD(id, pad) {
            return id.toString().padStart(pad, '0');
        },
    },
}
</script>
