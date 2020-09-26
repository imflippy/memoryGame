import Vue from 'vue'
import VueRouter from 'vue-router'



import Home from '@/js/Views/Home.vue'
import About from '@/js/Views/About.vue'
import Login from '@/js/Views/Login.vue'
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
