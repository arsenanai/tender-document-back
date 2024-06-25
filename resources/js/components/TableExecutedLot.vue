<template>
    <div>
        <h1 class="fs-2 text-capitalize text-center text-md-start">
            {{ entity.label }}
        </h1>
        <div
            class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 mb-2"
        >
            <button
                class="btn btn-sm btn-light"
                @click="$emit('new-entity', entity.route)"
            >
                {{ $t("Add New") }}
            </button>
            <search-form @on-search="onSearch" />
            <div
                class="d-flex flex-row align-items-center gap-2"
                aria-label="Page navigation"
                v-if="entity.page !== null && entity.page.to > 0"
            >
                <span
                    >{{ entity.page.from }} - {{ entity.page.to }} &#47;
                    {{ entity.page.total }}</span
                >
                <ul class="pagination my-0">
                    <li class="page-item" :class="{ disabled: !hasPrevPage() }">
                        <a
                            class="page-link fw-bold"
                            :class="{ 'text-dark': hasPrevPage() }"
                            @click="$emit('on-prev')"
                            >&lt;</a
                        >
                    </li>
                    <li class="page-item" :class="{ disabled: !hasNextPage() }">
                        <a
                            class="page-link fw-bold"
                            :class="{ 'text-dark': hasNextPage() }"
                            @click="$emit('on-next')"
                            >></a
                        >
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-responsive mb-2">
            <table
                class="table table-hover text-nowrap table-sm align-middle"
                v-if="entity.page !== null && entity.page.to > 0"
            >
                <thead>
                    <tr>
                        <th scope="col" v-if="entity.withIndex">&#x2116;</th>
                        <th scope="col" v-else>ID</th>
                        <th
                            scope="col"
                            v-for="(fillable, i) in entity.fillables"
                            :key="i"
                        >
                            {{ fillable.label }}
                        </th>
                        <th scope="col">
                            {{ $t("Actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data, i) in entity.page.data" :key="i">
                        <td class="fw-bold font-monospace">
                            {{ getId(data, i) }}
                        </td>
                        <td
                            v-for="(fillable, j) in entity.fillables"
                            :key="j"
                            :class="{
                                'table-success':
                                    data.result[fillable.name] === true,
                                'table-danger':
                                    data.result[fillable.name] === false,
                            }"
                        >
                            <span
                                v-if="
                                    fillable.hasOwnProperty('raw') &&
                                    fillable.data === 'raw'
                                "
                                :class="
                                    callFunction('class', fillable, data.result)
                                "
                                v-html="fillable.raw(data.result, i)"
                            >
                            </span>
                            <template v-else>
                                <!-- {{ fillable.name }} - -->

                                {{
                                    typeof data.result[fillable.name] ===
                                    "boolean"
                                        ? data.result[fillable.name]
                                            ? $t("true")
                                            : $t("false")
                                        : data.result[fillable.name]
                                }}

                                <span
                                    v-if="fillable.name === 'partnerName'"
                                    v-html="data[fillable.name]"
                                >
                                </span>
                            </template>
                        </td>
                        <td>
                            <a
                                class="btn btn-light btn-sm"
                                @click="$emit('on-edit', data)"
                                >{{ $t("Completed") }}</a
                            >
                        </td>
                    </tr>
                </tbody>
            </table>

            <p v-else-if="loading">{{ $t("Loading data") }}...</p>
            <h6 v-else>
                <i>{{ $t("No entries") }}</i>
            </h6>
        </div>
    </div>
</template>

<script>
import SearchForm from "./SearchForm.vue";
import common from "@/mixins/common";

export default {
    name: "TableExecutedLot",
    mixins: [common],
    props: {
        title: String,
        entity: {
            required: true,
            type: Object,
        },
        loading: Boolean,
    },
    components: {
        SearchForm,
    },
    methods: {
        getId(data, i) {
            const id = this.entity.withIndex
                ? i + this.entity.page.from
                : data.id;
            return 0;
        },

        goToDetail(lotNumber) {
            this.$router.push({
                name: "number.show",
                params: { lotNumber: lotNumber },
            });
        },
        hasPrevPage() {
            return this.entity.page.prev_page_url !== null;
        },
        hasNextPage() {
            return this.entity.page.next_page_url !== null;
        },
        onSearch(input) {
            this.$emit("onSearch", input);
        },
        onDelete(data) {
            if (
                confirm(
                    `${this.$t("You are deleting an item from")}: ${this.$t(
                        this.toTitleCase(this.entity.route)
                    )}. ${this.$t(
                        "This will delete all it's child entries as well if there are. Are you sure"
                    )}?`
                )
            ) {
                this.$emit("on-delete", data);
            }
        },
        getPagination() {
            return parseInt(import.meta.env.VITE_PAGINATION_SIZE);
        },
        callFunction(fn, object, input) {
            if (typeof object[fn] === "function") {
                return object[fn](input);
            }
            return null;
        },
    },
};
</script>

<style scoped>
h3.mx-auto {
    margin: 0 auto;
}
.container {
    width: 100%;
    margin-top: 20px;
}

.header {
    display: flex;
    align-items: center;
}

.table-container {
    width: auto;
}

.table-responsive {
    border: 1px solid #dee2e6;
}

.table-success {
    background-color: #d4edda !important;
}

.table-danger {
    background-color: #f8d7da !important;
}

.btn-primary {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
