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
import Form from "@/components/Form.vue";
import common from "@/mixins/common";
import forms from "@/mixins/forms";

export default {
    components: {
        Form,
    },
    name: "PartnerIDEdit",
    mixins: [common, forms],
    data() {
        return {
            entity: {
                label: this.$t("Partner ID Edit Form"),
                route: "partner-ids",
                pad: parseInt(import.meta.env.VITE_PAD_SUBPARTNER_ID),
                fillables: [
                    {
                        codename: "id",
                        type: "hidden",
                        required: true,
                    },
                    {
                        codename: "partner_id",
                        type: "autocomplete",
                        autocomplete: {
                            for: "partner",
                            selectionField: "id",
                            displayField: "name",
                            minChars: 3,
                            link: "/api/partners",
                            method: "GET",
                        },
                        title: this.$t("Partner"),
                        validationMessage: this.$t("This field is required"),
                    },
                    {
                        codename: "number_id",
                        type: "autocomplete",
                        dependsOn: "partner_id",
                        autocomplete: {
                            for: "number",
                            selectionField: "id",
                            displayField: "lotNumber",
                            minChars: 3,
                            link: "/api/numbers",
                            method: "GET",
                        },
                        title: this.$t("Number"),
                        required: true,
                        validationMessage: this.$t("This field is required"),
                    },
                    {
                        codename: "subpartner_id",
                        type: "autocomplete",
                        dependsOn: "partner_id",
                        autocomplete: {
                            for: "subpartner",
                            selectionField: "id",
                            displayField: "name",
                            minChars: 3,
                            link: "/api/subpartners",
                            method: "GET",
                        },
                        title: this.$t("Subpartner"),
                        required: true,
                        validationMessage: this.$t("This field is required"),
                    },
                    {
                        codename: "comments",
                        type: "textarea",
                        title: this.$t("Comments"),
                    },
                ],
                // fillables here
                comments: null,
                partner_id: null,
                partner: {
                    name: null,
                },
                subpartner_id: null,
                subpartner: {
                    name: null,
                },
                number_id: null,
                number: {
                    lotNumber: null,
                },
                // fillables end
            },
            submit: this.$t("Update"),
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
};
</script>
