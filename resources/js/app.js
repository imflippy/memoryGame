import Vue from 'vue'
import App from '@/js/App.vue'
import router from '@/js/Router/index.js'
import store from '@/js/Store/index.js'
import axios from 'axios'
import VueWindowSize from 'vue-window-size';

import FrontLayout from '@/js/Layouts/Front.vue';
import AdminLayout from '@/js/Layouts/Admin.vue';
import {mapGetters} from "vuex";

Vue.config.productionTip = false;

const rootUrl = document.location.protocol +"//" + document.location.hostname + ":" +document.location.port;
axios.defaults.baseURL = rootUrl + '/api/';


Vue.component('Front-layout', FrontLayout)
Vue.component('Admin-layout', AdminLayout)


Vue.use(VueWindowSize);




const app =  new Vue({
    el: '#app',
    router,
    store,
    computed: {
        ...mapGetters([
            'user'
        ])
    },
    created () {
        const userInfo = this.user;
        if (userInfo) {
            this.$store.dispatch('setUserInfo', userInfo);
        }
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response.status === 401) {
                    this.$store.dispatch('logout');
                } else if(error.response.status === 419) {
                    alert(error.response.data.error)
                    this.$store.dispatch('logout');
                }
                return Promise.reject(error)
            }
        )
    },
    render: h => h(App)
});

export default app;
