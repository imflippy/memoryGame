require('./bootstrap');

import Vue from 'vue'
import App from '@/js/App.vue'
import router from '@/js/Router/index.js'
import store from '@/js/Store/index.js'
import axios from 'axios'
//import VueWindowSize from 'vue-window-size'; //ovo treba obrisati
import Vuelidate from 'vuelidate'


import FrontLayout from '@/js/Layouts/Front.vue';
import AdminLayout from '@/js/Layouts/Admin.vue';
import {mapGetters} from "vuex";

Vue.config.productionTip = false;

const rootUrl = document.location.protocol +"//" + document.location.hostname + ":" +document.location.port;
axios.defaults.baseURL = rootUrl + '/api/';
axios.defaults.showLoader = true;

axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
};



Vue.component('Front-layout', FrontLayout)
Vue.component('Admin-layout', AdminLayout)

Vue.use(Vuelidate);
// Vue.use(VueWindowSize);




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
        //loader start
      axios.interceptors.request.use(
        config => {
          if (config.showLoader) {
            store.dispatch('loader/pending');
          }
          return config;
        },
        error => {
          if (error.config.showLoader) {
            store.dispatch('loader/done');
          }
          return Promise.reject(error);
        }
      );
      axios.interceptors.response.use(
        response => {
          if (response.config.showLoader) {
            store.dispatch('loader/done');
          }

          return response;
        },
        error => {
          let response = error.response;

          if (response.config.showLoader) {
            store.dispatch('loader/done');
          }

          return Promise.reject(error);
        }
      )
      //loader ends
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response.status === 403) {
                    this.$store.dispatch('logout');
                    this.$router.push('/');
                } else if(error.response.status === 419) {
                    this.$store.dispatch('logout');
                    this.$router.push('/');
                }
                if(!this.$router.currentRoute.path.includes('login')) {
                  this.$router.push('/login')
                }
                return Promise.reject(error)
            }
        )
    },
    render: h => h(App)
});

export default app;
