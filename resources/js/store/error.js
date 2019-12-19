const state = {
    status: null
}

const mutations = {
    setCode(state, code) {
        state.status = code
    }
}

export default {
    namespaced: true,
    state,
    mutations
}
