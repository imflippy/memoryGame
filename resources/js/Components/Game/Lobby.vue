<template>
  <div>
    <span>Logged Users: {{ getOnlineUsers.length }}</span>
    <span>Lobby Users: {{ lobbyUsers.length }}</span>
<!--    <ul v-if="getOnlineUsers.length > 0">-->
<!--      <li  v-for="(value, key) in getOnlineUsers">{{key}}. {{value.user.name}}</li>-->
<!--    </ul>-->
  </div>

</template>

<script>
  import axios from "axios";

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
        lobbyUsers: []
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
      Echo.join('lobby')
        .here((users) => {
          console.log("HEREHEREHEREHEREHERE", users);
          this.lobbyUsers = users;
        })
        .joining((user) => {
          console.log("JOININGJOININGJOININGJOININGJOINING", user);
          this.lobbyUsers.push(user);
        })
        .leaving((user) => {
          console.log('LEAVINGLEAVINGLEAVINGLEAVINGLEAVING', user);
          this.lobbyUsers.splice(this.lobbyUsers.indexOf(user), 1);
        });
    },
    destroyed() {
      Echo.leave('lobby');
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Lobby.scss";
</style>

