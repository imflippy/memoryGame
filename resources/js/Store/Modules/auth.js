import axios from "axios";

const state = {
    user: null,
};

const getters = {
    isLogged: state => !!state.user,
    user: state => state.user
};

const mutations = {
    setUserData (state, userData) {
        state.user = userData;
        //this.$store.storage.setItem('user', JSON.stringify(userData.user));
        axios.defaults.headers.common.Authorization = `Bearer ${userData.access_token}`
    },

    clearUserData (state) {
        state.user = null;
        //this.$store.storage.removeItem('user')
        location.reload()
    }
};

const actions = {
    login ({ commit }, credentials) {
        return axios
        .post('/auth/login', credentials)
        .then(({ data }) => {
            commit('setUserData', data)
        })
        .catch(e => {
            console.log(e)
        })
    },
    setUserInfo({commit}, userData) {
        commit('setUserData', userData);
    },
    logout ({ commit }) {
        commit('clearUserData')
    }
};
export default {
    state,
    getters,
    mutations,
    actions
}

