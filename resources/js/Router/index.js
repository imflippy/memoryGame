import Vue from 'vue'
import VueRouter from 'vue-router'



import Home from '@/js/Components/Home/Home.vue'
import About from '@/js/Views/About.vue'
import Login from '@/js/Components/Login/Login.vue'
import Register from '@/js/Components/Register/Register.vue'
import Lobby from '@/js/Components/Game/Lobby.vue'
import Game from '@/js/Components/Game/Game.vue';

import {mapGetters} from "vuex";
import store from "../Store";


Vue.use(VueRouter)

const routes = [
  {
      path: '/',
      name: 'Home',
      component: Home
  },
  {
      path: '/about',
      name: 'About',
      component: About,
      meta: {
          auth: true
      }
  },
  {
      path: '/login',
      name: 'Login',
      component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/lobby',
    name: 'Lobby',
    component: Lobby,
    meta: {
      auth: true
    }
  },
  {
    path: '/game/:id',
    name: 'Game',
    component: Game,
    meta: {
      auth: true
    }
  }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes,
    computed: {
        ...mapGetters([
            'user'
        ])
    }
});

router.beforeEach((to, from, next) => {
    const loggedIn = store.getters.isLogged;

    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next('/login')
        return
    }
    next()
})

export default router
