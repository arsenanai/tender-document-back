<template>
    <div class="container">
        <p class="text-center">
            <span v-if="loading">Logging out...</span>
        </p>
    </div>
</template>
<script>
import common from '../common';
export default {
    mixins: [common],
    data() {
        return {
            token: null,
            loading: false,
        };
    },
    methods: {
        sendLogoutRequest(){
            //if(this.token!=null){
                this.loading = true
                axios({
                    method: 'POST',
                    url: '/api/logout',
                    withCredentials: true,
                    headers: {
                        'Authorization': `Bearer ${this.token}`,
                    }
                })
                .then((response) => {
                    console.log(response)
                }).catch(error => {
                    console.log(error)
                }).then(_ => {
                    this.loading = false
                    this.eraseUserData()
                    this.goTo(this.$router, {name: "auth.login"});
                })
            //}
        },
    },
    created() {
        this.token = this.getAuthenticatedUser().token;
        this.sendLogoutRequest()
    }
}
</script>