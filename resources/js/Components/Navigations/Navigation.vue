<template>
    <div id="nav">
        <router-link
          v-for="(nl, index) of navigationLinks"
          v-if="isLogged === nl.auth"
          :key="index"
          :to="nl.href"
          class="nav-link">
          {{ nl.name }}
        </router-link>

        <a class="nav-link" @click="logoutUser" v-if="isLogged">Logout</a>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {navigationLinks} from "../../variables";
    export default {
        name: "Navigation",
        computed: {
            ...mapGetters([
                'isLogged'
            ]),
        },
        data() {
          return {
            navigationLinks
          }
        },
        methods: {
            async logoutUser () {
                await this.$store.dispatch('logout');
                await this.$router.push('/');
            }
        }
    }
</script>

<style lang="scss" >
  @import "./../../../sass/variables.scss";
  @import "./scss/Navigation.scss";
</style>
