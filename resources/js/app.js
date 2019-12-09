require('./bootstrap');

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import router from './routes';
import App from './App.vue';

Vue.use(BootstrapVue);

new Vue({
    el:'#app',
    router,
    components:{App},
    template:'<App />'
})
