import {CREATED, OK, VALIDATION_ERROR} from "../const/httpStatus";

const state = {
    user: null,
    apiStatus: null,
    registerErrors: null,
    loginErrors:null
};
const getters = {
    check: state => state.user !== null,
    userName: state => state.user ? state.user.name : ''
};
const mutations = {
    setUser(state, user) {
        state.user = user;
    },
    setApiStatus(state, status) {
        state.apiStatus = status;
    },
    setRegisterErrors(state, errors) {
        state.registerErrors = errors;
    },
    setLoginError(state,errors){
        state.loginErrors = errors;
    }
};
const actions = {
    // ユーザーの登録
    async register(context, data) {
        context.commit('setApiStatus',null);
        const response = await axios.post('/api/register', data);
        const status = response.status;
        if (status === CREATED) {
            context.commit('setUser', response.data);
            context.commit('setApiStatus', true);
            return false;
        }
        context.commit('setApiStatus', false);
        if (status === VALIDATION_ERROR) {
            context.commit('setRegisterErrors', response.data.errors);
        } else {
            context.commit('error/setCode', status, {root: true});
        }
    },
    // ユーザーのログアウト
    async logout(context) {
        context.commit('setApiStatus', null);
        const response = await axios.post('/api/logout');
        const status = response.status;
        if (status === OK) {
            context.commit('setApiStatus', true);
            context.commit('setUser', null);
            return false;
        }
        context.commit('setApiStatus', false);
        context.commit('error/setCode', status, {root: true});
    },
    // Delete register error
    deleteRegisterErrors(context) {
        context.commit('setRegisterErrors', null);
    },

    // Login
    async login(context,data){
        context.commit('setApiStatus',null);
        const response = await axios.post('/api/login',data);
        const status = response.status;
        if (status === OK){
            context.commit('setUser', response.data);
            context.commit('setApiStatus',true);
            return false;
        }
        context.commit('setApiStatus', false);
        if (status === VALIDATION_ERROR) {
            context.commit('setLoginError', response.data.errors);
        } else {
            context.commit('error/setCode', status, {root: true});
        }
    },

    // delete login errors
    deleteLoginErrors(context)
    {
        context.commit('setLoginError',null);
    },

    // Current User
    async currentUser(context){
        const response = await axios.get('/api/user');
        const user = response.data || null;
        context.commit('setUser', user);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
