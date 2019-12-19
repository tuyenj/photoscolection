require('./bootstrap');

import Vue from 'vue';
import router from './routes';
import store from './store';
import BootstrapVue from 'bootstrap-vue'
import App from './App.vue';

Vue.use(BootstrapVue);
(async () => {
    await store.dispatch('auth/currentUser');
    new Vue({
        el: '#app',
        router,
        store,
        render: h => h(App)
    });
})();

