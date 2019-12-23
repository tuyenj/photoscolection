import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';

import Home from '../pages/Home';
import Register from '../pages/Register';
import Login from '../pages/Login';
import AddPhoto from '../pages/AddPhoto';
import SystemError from '../pages/SystemError';
import PageNotFound from '../pages/PageNotFound';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home-page',
        component: Home
    },
    {
        path: '/register',
        name: 'register-page',
        component: Register,
        beforeEnter(to, from, next) {
            if (store.getters['auth/check']) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/login',
        name: 'login-page',
        component: Login,
        beforeEnter(to, from, next) {
            if (store.getters['auth/check']) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/photo/new',
        name: 'add-photo-page',
        component: AddPhoto,
        beforeEnter(to, from, next) {
            if (store.getters['auth/check']) {
                next();
            } else {
                next('/login');
            }
        }

    },
    {
        path: '/system-error',
        name: 'system-error',
        component: SystemError
    },
    {
        path: '/page-not-found',
        name: 'page-not-found',
        component: PageNotFound
    }
];
const router = new VueRouter({
    mode: 'history',
    routes
})
export default router;
