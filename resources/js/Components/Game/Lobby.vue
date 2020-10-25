<template>
  <div>
    <span>Logged Users: {{ getOnlineUsers.length }}</span>
    <ul>
      <li v-for="(value, key) in getOnlineUsers">{{key}}. {{value.user.name}}</li>
    </ul>
  </div>

</template>

<script>
  import axios from "axios";
  import Echo from "laravel-echo";

  const msgs = [];
  const idCh = Math.floor(Math.random() * 1000);

  import Pusher from 'pusher-js' //ima opcija da se doda da bude enkriptovano sve, pogmedaj na !npm
  Pusher.logToConsole = true;


  import Loader from "../Helpers/Loader";
  import {mapGetters} from "vuex";

  export default {
    name: "Lobby",
    components: {
      'loader': Loader
    },
    data() {
      return {
      }
    },
    methods: {
      joinQue() {

      }
    },
    computed: {
      ...mapGetters(['user', 'getOnlineUsers']),

    },
    mounted() {

    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Lobby.scss";
</style>

