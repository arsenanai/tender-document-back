<template>
    <Form
        :entity="entity"
        :submit="submit"
        :alert="alert"
        :loading="loading"
        @onSubmit="onSubmit('Creation')"
        @onKeyup="onKeyup"
    />
</template>

<script>
import Form from "@/components/Form.vue";
import common from "@/mixins/common";
import forms from "@/mixins/forms";
import prevalidation from "@/mixins/prevalidation";

export default {
    name: "NumberCreate",
    components: {
        Form,
    },
    mixins: [common, forms, prevalidation],
    data() {
        return {
            entity: {
                label: this.$t("Number Create Form"),
                route: "numbers",
                fillables: [
                    {
                        codename: "partner_id",
                        type: "autocomplete",
                        autocomplete: {
                            for: "partner",
                            selectionField: "id",
                            displayField: "name",
                            minChars: 8,
                            link: "/api/partners",
                            method: "GET",
                        },
                        title: this.$t("Partner"),
                        required: true,
                        validationMessage: this.$t("This field is required"),
                    },
                    {
                        codename: "lotNumber",
                        type: "text",
                        title: this.$t("Lot number"),
                        required: true,
                        validationMessage: this.$t("This field is required"),
                        preValidation: {
                            link: "/api/numbers",
                            method: "GET",
                            minChars: 3,
                            message: (data, input, fillable) => {
                                if (data.length > 1) {
                                    fillable.feedbackMessage = `${this.$t(
                                        "Already in use by"
                                    )} <a target="_blank"
                  href="/numbers?search=${input}&filterBy=lotNumber">${
                                        data.length
                                    } ${this.$t("numbers")}</a>`;
                                    fillable.hasError = true;
                                } else if (data.length === 1) {
                                    fillable.feedbackMessage = `${this.$t(
                                        "Already in use by"
                                    )} <a target="_blank"
                  href="/numbers/edit/${data[0].id}">${
                                        data[0].partner.name
                                    }</a>`;
                                    fillable.hasError = true;
                                }
                            },
                        },
                    },
                    {
                        codename: "procurementNumber",
                        type: "text",
                        title: this.$t("Procurement number"),
                        required: true,
                        validationMessage: this.$t("This field is required"),
                        preValidation: {
                            link: "/api/numbers",
                            method: "GET",
                            minChars: 3,
                            message: (data, input, fillable) => {
                                if (data.length > 1) {
                                    fillable.feedbackMessage = `${this.$t(
                                        "Already in use by"
                                    )} <a target="_blank"
                  href="/numbers?search=${input}&filterBy=procurementNumber">${
                                        data.length
                                    } ${this.$t("numbers")}</a>`;
                                    fillable.hasError = true;
                                } else if (data.length === 1) {
                                    fillable.feedbackMessage = `${this.$t(
                                        "Already in use by"
                                    )} <a target="_blank"
                  href="/numbers/edit/${data[0].id}">${
                                        data[0].partner.name
                                    }</a>`;
                                    fillable.hasError = true;
                                }
                            },
                        },
                    },
                ],
                // fillables here
                name: null, // must match the codename
                partner_id: null,
                partner: {
                    name: null,
                },
                // fillables end
            },
            submit: this.$t("Create"),
            loading: false,
            alert: {
                type: null,
                message: null,
            },
            data: null,
        };
    },
};
</script>
