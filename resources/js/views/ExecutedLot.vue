<template>
    <TableExecutedLot
        :entity="entity"
        :loading="loading"
        @new-entity="newEntity"
        @on-next="onNext"
        @on-prev="onPrev"
        @on-edit="onEdit"
        @on-show="onShow"
        @on-delete="onDelete"
        @on-search="onSearch"
    />
</template>

<script>
import common from "@/mixins/common";
import paginationLot from "@/mixins/paginationLot";
import TableExecutedLot from "../components/TableExecutedLot.vue";
export default {
    name: "ExecutedLot",
    components: {
        TableExecutedLot,
    },
    mixins: [common, paginationLot],
    data() {
        return {
            url: import.meta.env.VITE_PARSER_URL,
            entity: {
                label: this.$t("Numbers"),
                route: "executedlot",
                pad: parseInt(import.meta.env.VITE_PAD_PARTNER_ID),
                fillables: [
                    {
                        label: this.$t("Partner"),
                        name: "partnerName",

                        raw: (data) => data.partnerName,
                    },
                    {
                        label: this.$t("Announcement"),
                        name: "announcement",
                        raw: (data) => data.result.announcement,
                    },
                    {
                        label: this.$t("Number"),
                        name: "number",
                        raw: (data) => data.result.number,
                    },

                    {
                        label: this.$t("Lot number"),
                        name: "lotNumber",
                        raw: (data) => data.result.lotNumber,
                    },
                    {
                        label: this.$t("BIN"),
                        name: "bin",
                        raw: (data) => data.result.bin,
                    },
                    {
                        label: this.$t("Text"),
                        name: "isTextOk",
                        raw: (data) => data.result.isTextOk,
                    },
                    {
                        label: this.$t("LabWorkAmount"),
                        name: "isLabWorkAmountOk",
                        raw: (data) => data.result.isLabWorkAmountOk,
                    },
                    {
                        label: this.$t("ProjectAmount"),
                        name: "isProjectAmountOk",
                        raw: (data) => data.result.isProjectAmountOk,
                    },
                    {
                        label: this.$t("Comment"),
                        name: "comment",
                        raw: (data) => data.result.comment,
                    },
                    {
                        label: this.$t("Modified at"),
                        name: "Modified at",
                        data: "raw",
                        raw: (data) =>
                            data.modified.replaceAll("T", " ").substring(0, 16),
                    },

                    {
                        label: this.$t("Created at"),
                        data: "raw",
                        raw: (data) =>
                            data.created.replaceAll("T", " ").substring(0, 16),
                    },
                ],
                page: null,
            },
        };
    },
};
</script>
