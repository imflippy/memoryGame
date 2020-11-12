<template>
  <div class="game-wrapper">
      <div class="game-header"></div>
      <div class="game-body">
        <div class="all-cards">
          <div class="game-card" v-for="(card, cId) in allCards" :key="cId">{{ card.id }}</div>
        </div>
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
      methods: {
        getCards() {
          let params = {
            gameId: this.getGameId
          }
          axios.get('/get-cards', {params: params})
            .then((res) => {
              this.allCards = res.data;
            })
            .catch((err) => {
              console.log('ERROR WITH CAREDS', err)
            })
        },
        shuffle(array) {
          var currentIndex = array.length, temporaryValue, randomIndex;

          // While there remain elements to shuffle...
          while (0 !== currentIndex) {

            // Pick a remaining element...
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;

            // And swap it with the current element.
            temporaryValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = temporaryValue;
          }

          return array;
        },
        listenToUsersActivity() {
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
            .listen('.GenerateCards', (res) => {
              console.log("LISTEN", res);
              this.allCards = res.cards;
            })
        }
      },
      mounted() {
        // setujem gameId da bih mogao u beforeDestroy da dohvatim Id i leave socket
        this.getGameId = this.$route.params.id;

        this.listenToUsersActivity();
        // Echo.private(`game.${this.getGameId}`)
        //   .listen('.game-info', (payload) => {
        //     console.log(data, "GAME DATA");
        //   });
         this.getCards(); //dohvatam inicijalno sve karte



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
