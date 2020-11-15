<template>
  <div class="game-wrapper">
      <div class="game-header"></div>
      <div class="game-body">
        <div class="all-cards">
          <game-card
            v-for="(card, cId) in allCards"
            :key="cId"
            :card="card"
            :positionId="cId"
            :is-opened="currentRoundCardsKeys.includes(cId)"
            :is-my-card="myCards.includes(card.id)"
            :is-oppoent-card="opponentCards.includes(card.id)"
            @openCard="openCard"
            ></game-card>
        </div>
      </div>
  </div>
</template>

<script>

    import {mapGetters} from "vuex";

    import GameCard from "./GameCard";
    export default {
      name: "Game",
      components: {
        GameCard
      },
      data() {
        return {
          gameUsers: [],
          getGameId: 0,
          allCards: [],
          currentRoundCardsKeys: [],
          currentRoundCards: [],
          myCards : [],
          opponentCards: [],
          playerTurn: false //stavi false
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
              if(this.gameUsers.length === 1) {
                this.gameUsers.push(user);
                this.playerTurn = true;
              }
            })
            .leaving((user) => {
              if(this.gameUsers.length === 2) {
                console.log("POBEDIK JE " + this.user.user.name);
                //  TODO: Ovde treba uraditi redirect od trenutng usera na rutu gde je on pobedio mec - tek kada se uradi
              }
            })
            .listen('.GenerateCards', (res) => {
              // console.log("LISTEN", res);
              this.allCards = res.cards;
            })
            .listen('GameEvent', (res) => {
              let newCurrRoundCardKeys = res.data.currentRoundCardsKeys;
              let newCurrRoundCard = res.data.currentRoundCards;
              let sameCardId = res.data.sameCardId;

              this.currentRoundCardsKeys = newCurrRoundCardKeys;
              this.currentRoundCards = newCurrRoundCard;

              if(newCurrRoundCardKeys.length === 2) {
                setTimeout(() => {
                  if(sameCardId !== 0) {
                    this.playerTurn ? this.myCards.push(sameCardId) : this.opponentCards.push(sameCardId);
                  }
                }, 500)

                setTimeout(() => {
                  this.currentRoundCardsKeys = [];
                  this.currentRoundCards = [];

                  if(res.data.changeUser) {
                    this.playerTurn = !this.playerTurn;
                  }
                }, 2000);
              }
            })
        },
        openCard(cardDetails) {
          if(this.currentRoundCardsKeys.length < 2 && this.playerTurn) {
            let currentGame = {
              currentRoundCardsKeys: this.currentRoundCardsKeys,
              currentRoundCards: this.currentRoundCards
            }
            let req = {
              gameId: this.getGameId,
              currentGame: currentGame,
              cardDetails: cardDetails
            }

            this.callOpenCard(req);
          }
          // this.currentRoundCardsKeys.push(positionId);
        },
        callOpenCard(req) {
          axios.post('/open-card', req)
            .then((res) => {
              console.log("POST", res.data);
            })
            .catch((err) => {
              console.log('ERROR CARd playYYY', err)
            })
        }
      },
      mounted() {
        // setujem gameId da bih mogao u beforeDestroy da dohvatim Id i leave socket
        this.getGameId = this.$route.params.id;

        this.listenToUsersActivity();

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
