import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import createPersistedState from "vuex-persistedstate";
import * as Cookies from "js-cookie";

//import modules here
import auth from './Modules/auth';
import settings from './Modules/settings';

Vue.use(Vuex);


const store = new Vuex.Store({
    modules: {
        auth,
        settings
    },
    plugins: [
        createPersistedState({
            storage: {
                getItem: (key) => Cookies.get(key),
                setItem: (key, value) =>
                    Cookies.set(key, value, { expires: 3, secure: false }), //ako je secure na true, radi samo na https protokolu
                removeItem: (key) => Cookies.remove(key),
            },
            paths: ['auth.user'] // proveriti da li radi samo za auth ili za sve
        }),
    ]
})

export default store;
