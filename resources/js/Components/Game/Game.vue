<template>
  <div class="game-wrapper">
      <div class="game-header"></div>
      <div class="game-body">
        <div class="game-card"></div>
        <div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div>
        <div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div>
        <div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div>
        <div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div><div class="game-card"></div>




      </div>
  </div>
</template>

<script>

    import {mapGetters} from "vuex";
    export default {
      name: "Game",
      data() {
        return {
          gameUsers: [],
          getGameId: 0,
          allCards: []
        }
      },
      computed: {
        ...mapGetters(['user', 'getOnlineUsers'])
      },
      mounted() {
        // setujem gameId da bih mogao u beforeDestroy da dohvatim Id i leave socket
        this.getGameId = this.$route.params.id;
        // Echo.private(`game.${this.getGameId}`)
        //   .listen('.game-info', (payload) => {
        //     console.log(data, "GAME DATA");
        //   });


        Echo.join('game-info-users.' + this.getGameId)
          .here((users) => {
            console.log("HEREHEREHEREHEREHEREGAME", users);
            if(users.length > 2) {
              console.log("NIJE TVOJ MEC")
            } else {
              this.gameUsers = users;
            }
          })
          .joining((user) => {
            // console.log("JOININGJOININGJOININGJOININGJOININGGAME", user);
            if(this.gameUsers.length === 1) {
              this.gameUsers.push(user);
            }
          })
          .leaving((user) => {
            // console.log('LEAVINGLEAVINGLEAVINGLEAVINGLEAVINGGAME', user);
            if(this.gameUsers.length === 2) {
              console.log("POBEDIK JE " + this.user.user.name);
              //  TODO: Ovde treba uraditi redirect od trenutng usera na rutu gde je on pobedio mec - tek kada se uradi
            }
          })

      },
      beforeDestroy() {
        Echo.leave('game-info-users.' + this.getGameId);
      }
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Game.scss";
</style>
