<template>
    <div id="app">
      <component :is="layout || 'div'">
          <router-view />
      </component>
    </div>
</template>

<script>

  import {mapGetters, mapState} from "vuex";
  import Loader from './Components/Helpers/Loader';
    export default {
      components: {
        'loader': Loader
      },
      computed: {
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
          this.$store.dispatch('pingUser')
          let token = this.user && this.user.access_token || "token";
          if(token !== 'token') {
            Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer ' + token;
          }
        },
        authChannel() {
          Echo.join('auth')
            .here((users) => {
              // console.log("HEREHERE AUTH C", users);
              this.$store.dispatch('setOnlineUsersSocket', users);
            })
            .joining((user) => {
              // console.log("JOIN AUTH CH", user);
              this.$store.dispatch('addUserFromSocket', user);
            })
            .leaving((user) => {
              // console.log('LEAVE AUTH C', user);
              this.$store.dispatch('removeUserFromSocket', user.id);
            })
        }
      },
      mounted() {
        if(this.user)  {
          this.listenForBroadcast();
          this.authChannel();
        }
      },
      watch: {
        user(val) {
          if(val) {
            this.listenForBroadcast();
            this.authChannel();
          } else {
            //Ako se korisnik odjavi da izadje sa kanala, Ovo je verovatno moglo u logout mutaciji uraditi, ali kada vec imam watcher why not here
            Echo.leave('auth');
          }
        }
      },
      beforeDestroy() {
        Echo.leave('auth');
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
