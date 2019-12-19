<template>
    <div class="card card-block p-4">
        <b-tabs class="p-1" fill>
            <b-tab class="p-2" title="Register" active>
                <div class="form-group">
                    <label>名前</label>
                    <input type="text" class="form-control" id="name" placeholder="鈴木" v-model="user.name">
                    <div v-if="errors !== null">
                        <p class="text-danger" v-for="n in errors.name">{{n}}</p>
                    </div>
                </div>
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
                        <p class="text-danger" v-for="p in errors.password">{{p}}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label>もう一度パスワードを入力してください</label>
                    <input type="password" class="form-control" id="confirm_password"
                           v-model="user.password_confirmation">
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit" @click.prevent="userRegister">register</button>
                    <button class="btn btn-danger" @click="cancel">cancel</button>
                </div>
            </b-tab>
            <b-tab title="Login" @click="nextTab">
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
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                }
            }
        },
        created() {
            this.$store.dispatch('auth/deleteRegisterErrors');
        },
        computed: {
            ...mapState({
                status: state => state.auth.apiStatus,
                errors: state => state.auth.registerErrors
            })
        },
        methods: {
            async userRegister() {
                await this.$store.dispatch('auth/register', this.user);
                if (this.status) {
                    await this.$router.push('/');
                }
            },
            nextTab() {
                this.$router.push('/login');
            },
            cancel(){
                this.$router.push('/');
            }
        }
    }
</script>
