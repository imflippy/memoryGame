<template>
    <div id="nav">
        <router-link to="/">Home</router-link> |
        <router-link to="/about" v-if="isLogged">About</router-link>
        <router-link to="/login" v-else>Login</router-link>
        <button type="button" @click="logoutUser()" v-if="isLogged">
            Logout
        </button>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "Navigation",
        computed: {
            ...mapGetters([
                'isLogged'
            ]),
        },
        methods: {
            logoutUser () {
                this.$store.dispatch('logout');
            }
        }
    }
</script>

<style scoped>

</style>
