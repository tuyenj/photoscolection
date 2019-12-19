<template>
    <nav class="navbar navbar-dark bg-info">
        <div class="container">
            <div class="container-fluid">
                <div class="navbar-header">
                    <router-link tag="a" class="navbar-brand" to="/">Photo Collection</router-link>
                </div>
                <div v-if="isLogin">
                    <i class="far fa-user-circle p-1"></i>{{userName}}
                    <button class="btn btn-success" @click="userLogout">Logout</button>
                </div>
                <div v-else>
                    <router-link class="text-light" tag="a" to="/register">register</router-link>
                    /
                    <router-link class="text-light" tag="a" to="/login">login</router-link>
                </div>
            </div>
        </div>
    </nav>
</template>
<script>
    export default {
        computed: {
            isLogin() {
                return this.$store.getters['auth/check'];
            },
            userName() {
                return this.$store.getters['auth/userName'];
            }
        },
        methods: {
            async userLogout() {
                await this.$store.dispatch('auth/logout');
            }
        }
    }
</script>
