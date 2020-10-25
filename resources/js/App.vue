<template>
    <div id="app">
      <loader globalLoader="true" v-if="loading"/>
      <component :is="layout || 'div'" v-else>
          <router-view />
      </component>
    </div>
</template>

<script>

  import {mapGetters, mapState} from "vuex";
  import Loader from './Components/Helpers/Loader';
  // import Echo from "laravel-echo";
    export default {
      components: {
        'loader': Loader
      },
      computed: {
        ...mapState('loader', ['loading']),
        ...mapGetters(['user']),
          layout() {
              return (this.$route.meta.layout || 'Front') + '-layout'
          }
      },
      methods: {
        leaving() {
          if(this.user) this.$store.dispatch('logout');
        },
        listenForBroadcast() {
          let token = this.user && this.user.access_token || "token";
          if(token !== 'token') {
            Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + token;
          }
        }
      },
      mounted() {
        if(this.user)  {
          this.$store.dispatch('getOnlineUsersFromDB');
          this.listenForBroadcast();
        }
        Echo.channel('online-users')
          .listen('.online', (data) => {
            console.log("Usao je neko na kalan", data.newOnlineUser)
            this.$store.dispatch('addUserFromSocket', data.newOnlineUser);
          })

        Echo.channel('logout')
          .listen('.offline', (data) => {
            console.log('Neko je otisao, leaved ..')
            this.$store.dispatch('removeUserFromSocket', data.idUser);
          })
      },
      watch: {
        user(val) {
          if(val) {
            this.listenForBroadcast();
          }
        }
      }
    }
</script>

<style lang="scss">
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
  @import "../sass/variables";
  * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
  }
  body {
    background-color: $blueColor2;
  }
  a {
    text-decoration: none;
  }
  h1, h2, h3, h4, h5, h6 {
    letter-spacing: .3rem;
  }
  input, button {
    outline: unset;
    border: none;
  }

  ::-moz-selection { /* Code for Firefox */
    color: $textColorActive;
    background: #0E8A701a;
  }

  ::selection {
    color: $textColorActive;
    background: #0E8A701a;
  }
</style>
