<template>
    <div class="container">
        <div class="header d-flex align-items-center mb-3">
            <button @click="goBack" class="btn btn-primary mr-3">артқа</button>
            <h3 class="mx-auto">Лоттың тех-спецификациясы туралы ақпарат</h3>
        </div>
        <div class="row justify-content-center">
            <div class="table-container">
                <div v-if="posts">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Номер</th>
                                    <td>{{ posts.number }}</td>
                                </tr>
                                <tr>
                                    <th>Лот номері</th>
                                    <td>{{ posts.lotNumber }}</td>
                                </tr>
                                <tr>
                                    <th>BIN</th>
                                    <td>{{ posts.bin }}</td>
                                </tr>
                                <tr>
                                    <th>Текст</th>
                                    <td
                                        :class="{
                                            'table-success':
                                                posts.isTextOk === true,
                                            'table-danger':
                                                posts.isTextOk === false,
                                        }"
                                    >
                                        {{ posts.isTextOk ? "Дұрыс" : "Қате" }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Зертханалық жұмыс көлемі</th>
                                    <td
                                        :class="{
                                            'table-success':
                                                posts.isLabWorkAmountOk ===
                                                true,
                                            'table-danger':
                                                posts.isLabWorkAmountOk ===
                                                false,
                                        }"
                                    >
                                        {{
                                            posts.isLabWorkAmountOk
                                                ? "Дұрыс"
                                                : "Қате"
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Жоба көлемі</th>
                                    <td
                                        :class="{
                                            'table-success':
                                                posts.isProjectAmountOk ===
                                                true,
                                            'table-danger':
                                                posts.isProjectAmountOk ===
                                                false,
                                        }"
                                    >
                                        {{
                                            posts.isProjectAmountOk
                                                ? "Дұрыс"
                                                : "Қате"
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Өзгертілген</th>
                                    <td>
                                        {{ formatDateTime(posts.modified) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Құрылды</th>
                                    <td>{{ formatDateTime(posts.created) }}</td>
                                </tr>
                                <tr>
                                    <th>Пікір</th>
                                    <td
                                        :class="{
                                            'table-success':
                                                posts.comment === 'ok',
                                            'table-danger':
                                                posts.comment !== 'ok',
                                        }"
                                    >
                                        {{ posts.comment }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p v-else-if="noData">Мәліметтер енгізілмеген</p>
                <p v-else>Loading...</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import common from "@/mixins/common";
import forms from "@/mixins/forms";

export default {
    name: "NumberForm",
    mixins: [common, forms],
    data() {
        return {
            posts: null,
            noData: false,
            url: import.meta.env.VITE_PARSER_URL,
        };
    },
    created() {
        this.fetchPosts();
    },
    methods: {
        fetchPosts() {
            const lotNumb = this.$route.params.lotNumber;
            console.log(lotNumb);

            axios
                .get(`${this.url}/announcement/verify-result/${lotNumb}`)
                .then((response) => {
                    const announcement = response.data.result.announcement;
                    const numberId = response.data.result.id;
                    const e = 3;
                    if (announcement !== 0 && announcement !== null) {
                        response.data.result.formattedDate =
                            this.formatDateTime(e);
                        this.posts = response.data.result;
                    } else {
                        this.noData = true;
                    }
                })
                .catch((error) => {
                    console.error("Error fetching posts:", error);
                });
        },

        formatDateTime(dateTimee) {
            const date = new Date(dateTimee);
            const formattedDate = date.toISOString().split("T")[0];
            const formattedTime = date.toTimeString().split(" ")[0].slice(0, 5);
            return `${formattedDate} ${formattedTime}`;
        },

        goBack() {
            this.$router.go(-1);
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
