<template>
    <div class="card card-block p-4">
        <b-tabs class="p-1" fill>
            <b-tab class="p-2" title="Register" @click="nextTab">
            </b-tab>
            <b-tab class="p-2" title="Login" active>
                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com"
                           v-model="user.email">
                    <div v-if="errors !== null">
                        <p class="text-danger" v-for="e in errors.email">{{e}}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" class="form-control" id="password" v-model="user.password">
                    <div v-if="errors !== null">
                        <p class="text-danger" v-for="e in errors.password">{{e}}</p>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit" @click="userLogin">login</button>
                    <button class="btn btn-danger" @click="cancel">cancel</button>
                </div>
            </b-tab>
        </b-tabs>
    </div>
</template>
<script>
    import {mapState} from 'vuex';

    export default {
        data() {
            return {
                user: {
                    email: '',
                    password: ''
                }
            }
        },
        created() {
            this.$store.dispatch('auth/deleteLoginErrors');
        },
        computed: {
            ...mapState({
                status: state => state.auth.apiStatus,
                errors: state => state.auth.loginErrors
            })
        },
        methods: {
            async userLogin() {
                await this.$store.dispatch('auth/login', this.user);
                if (this.status) {
                    await this.$router.push('/');
                }
            },
            nextTab() {
                this.$router.push('/register');
            },
            cancel() {
                this.$router.push('/');
            }
        }
    }
</script>
