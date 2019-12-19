<template>
    <div>
        <MenuBar></MenuBar>
        <div class="container mt-1">
            <router-view/>
        </div>
    </div>
</template>
<script>
    import MenuBar from './components/Navbar.vue';
    import {UNAUTHORIZED, SYSTEM_ERRORS} from './const/httpStatus';

    export default {
        name: 'App',
        components: {
            MenuBar
        },
        computed:{
            errorCode(){
                return this.$store.state.error.status;
            }
        },
        watch: {
            errorCode: {
                async handler(val) {
                    if (val === SYSTEM_ERRORS) {
                        await this.$router.push('/system-error')
                    } else if (val === UNAUTHORIZED) {
                        await axios.get('/api/refresh-token');
                        await this.$router.push('/');
                    } else if (val === 404) {
                        await this.$router.push('/page-not-found');
                    }
                },
                immediate: true
            },
            $route(){
                this.$store.commit('error/setCode',null);
            }
        }
    }
</script>
