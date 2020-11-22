<template>
  <div class="lobby-wrapper">
    <div class="lobby-top">
      <div>Active: <span>{{ getOnlineUsers.length }}</span></div>
      <div>Waiting for match: <span>{{ lobbyUsers.length }}</span></div>
    </div>
    <div class="lobby-bot">
      <loader size="lobby" :globalLoader="true"></loader>
      <p>Looking for game...</p>
    </div>
<!--    <ul v-if="getOnlineUsers.length > 0">-->
<!--      <li  v-for="(value, key) in getOnlineUsers">{{key}}. {{value.user.name}}</li>-->
<!--    </ul>-->
  </div>

</template>

<script>
  import axios from "axios";

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
      generateGame() {
        if(this.lobbyUsers.length > 1) {
          console.log("Vise ih je od 1")
          axios.post('/auth/generate-game', {players: this.lobbyUsers}).
          then(() => {
            // console.log("USPEO POST")
          }).catch((ex) => {
            // console.log("NIJE USPEO", ex)
          })
        }
      },
      pickRandom(arr,count) {
        let _arr = [...arr];
        return[...Array(count)].map( ()=> _arr.splice(Math.floor(Math.random() * _arr.length), 1)[0] );
      }
    },
    computed: {
      ...mapGetters(['user', 'getOnlineUsers']),

    },
    mounted() {
      Echo.join('lobby')
        .here((users) => {
          // console.log("HEREHEREHEREHEREHERE", users);
          this.lobbyUsers = users;
        })
        .joining((user) => {
          // console.log("JOININGJOININGJOININGJOININGJOINING", user);
          this.lobbyUsers.push(user);
        })
        .leaving((user) => {
          // console.log('LEAVINGLEAVINGLEAVINGLEAVINGLEAVING', user);
          this.lobbyUsers.splice(this.lobbyUsers.indexOf(user), 1);
        })

      Echo.channel('generate-game-channel')
        .listen('.generate', (data) => {
          if(data.roomPlayersIds.includes(this.user.user.id) && this.lobbyUsers.length > 1) {
            this.$router.push('/game/' + data.roomId)
          }
        })

      setTimeout(() => {
        this.generateGame();
      }, 1000);
    },
    destroyed() {
      Echo.leave('lobby');
      Echo.leaveChannel('generate-game-channel');
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Lobby.scss";
</style>

