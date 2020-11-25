<template>
  <div class="game-wrapper">
      <div class="game-header">
        <user-stats
          :user="user.user"
          :is-on-turn="playerTurn"
          :player-cards="myCards.length"
          new-class="my-stats"
          :timer="timer"></user-stats>
        <div class="game-stats">
          <div class="remaining">
            <span class="text" v-if="getOpponentData !== undefined">Remaining</span>
            <span class="number" v-if="getOpponentData !== undefined">{{ calculateRemainingPairs }}</span>
          </div>
          <div class="stopwatch">
            {{ stopwatch }}
          </div>
        </div>
        <user-stats
          :user="getOpponentData"
          :is-on-turn="!playerTurn"
          :player-cards="opponentCards.length"
          new-class="opponent-stats"
          :timer="timer"></user-stats>
      </div>
      <div class="game-body">
        <div class="all-cards" v-if="getOpponentData !== undefined">
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
        <div v-else class="game-loader">
          <loader size="lobby" :globalLoader="true"></loader>
        </div>
      </div>
  </div>
</template>

<script>

    import {mapGetters} from "vuex";

    import GameCard from "./GameCard";
    import UserStats from "./UserStats";
    import Loader from "../Helpers/Loader";


    export default {
      name: "Game",
      components: {
        GameCard,
        UserStats,
        Loader
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
          playerTurn: false, //stavi false,
          stopwatchTime: 0,
          isCardAvailable: true, // da bi se spamovanje requestova zaustavilo
          timer: 0,
          startedGameTimestamp: null
        }
      },
      computed: {
        ...mapGetters(['user', 'getOnlineUsers']),
        calculateRemainingPairs() {
          return (this.allCards.length / 2)- this.myCards.length - this.opponentCards.length;
        },
        stopwatch() {
          let mins = Math.floor(this.stopwatchTime / 60);
          let secs = Math.floor(this.stopwatchTime - mins * 60);
          // let tenths = Math.floor(this.stopwatchTime % 10);

          if(mins <= 9){
            mins = "0" + mins;
          }
          if(secs <= 9){
            secs = "0" + secs;
          }
          // if(tenths <= 9){
          //   tenths = "0" + tenths;
          // }
          // return mins + ":" + secs + ":" + tenths;
          return mins + ":" + secs;
        },
        getOpponentData() {
          return this.gameUsers.filter(u => u.id !== this.user.user.id)[0];
        }
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
              // console.log('ERROR WITH CAREDS', err)
            })
        },
        listenToUsersActivity() {
          Echo.join('game-info-users.' + this.getGameId)
            .here((users) => {
              // console.log("HEREHEREHEREHEREHEREGAME", users);
              if(users.length > 2) {
                // console.log("NIJE TVOJ MEC")
                this.$router.push('/lobby');
              } else {
                this.getCards(); //dohvatam inicijalno sve karte
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
              if(this.gameUsers.length === 2 && this.calculateRemainingPairs !== 0 && user.id === this.getOpponentData.id ) {
                // console.log("POBEDIK JE " + this.user.user.name);

                let idUser = this.user.user.id;
                this.postGameStatus(idUser);
                this.$store.dispatch('setGameStatus', 'opponent-left');
                this.$router.push('/');
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
              let stopwatchTime = res.data.stopwatchTime;

              this.currentRoundCardsKeys = newCurrRoundCardKeys;
              this.currentRoundCards = newCurrRoundCard;
              this.stopwatchTime = stopwatchTime;

              if(newCurrRoundCardKeys.length === 2) {
                setTimeout(() => {
                  if(sameCardId !== 0) {
                    this.playerTurn ? this.myCards.push(sameCardId) : this.opponentCards.push(sameCardId);
                    //mozda i tu da reset timer - razmisli o biznis planu xd
                    this.timer = 0;
                  }
                }, 500)

                setTimeout(() => {
                  this.currentRoundCardsKeys = [];
                  this.currentRoundCards = [];

                  if(res.data.changeUser) {
                    this.playerTurn = !this.playerTurn;
                    this.timer = 0;
                  }
                }, 2000);
              }
            })
        },
        openCard(cardDetails) {
          if(this.currentRoundCardsKeys.length < 2 && this.playerTurn && this.isCardAvailable) {
            let currentGame = {
              currentRoundCardsKeys: this.currentRoundCardsKeys,
              currentRoundCards: this.currentRoundCards
            }
            let startedGameTimestamp = parseInt(this.startedGameTimestamp / 1000);

            let req = {
              gameId: this.getGameId,
              currentGame: currentGame,
              cardDetails: cardDetails,
              startedGameTimestamp: startedGameTimestamp
            }
            this.isCardAvailable = false;
            this.callOpenCard(req);
          }
          // this.currentRoundCardsKeys.push(positionId);
        },
        callOpenCard(paylaod) {
          axios.post('/open-card', paylaod)
            .then((res) => {
              this.isCardAvailable = true;
              // console.log("POST", res.data);
            })
            .catch((err) => {
              this.isCardAvailable = true;
              // console.log('ERROR CARd playYYY', err)
            })
        },
        postGameStatus(idUser) {
          let payload = {
            idUser: idUser
          }
          axios.post('/auth/win-game', payload)
            .then((res) => {
              // console.log('SUCCESS - postGameStatus');
            })
            .catch((err) => {
              // console.log("ERROR - postGameStatus", err)
            })
        }
      },
      mounted() {
        this.$store.dispatch('pingUser')
        this.$store.dispatch('setGameValue', true);
        // setujem gameId da bih mogao u beforeDestroy da dohvatim Id i leave socket
        this.getGameId = this.$route.params.id;
        this.listenToUsersActivity();
      },
      watch: {
        gameUsers(newVal) {
          if(newVal.length === 2) {
            this.startedGameTimestamp = Date.now();
            setInterval(() => {
              this.stopwatchTime = this.stopwatchTime + 1;
              this.timer = this.timer + 1;
            }, 1000);
          }
          //Ako korisnik ceka 5 sekundi, a protivnik nije usao u mec, vraca se u lobby
          setTimeout(() => {
            if(newVal.length === 1) {
              this.$router.push('/lobby');
            }
          }, 5000)
        },
        calculateRemainingPairs(newVal) {
          if(newVal === 0) {
            setTimeout(() => {
              if(this.myCards.length < this.opponentCards.length) {
                this.$store.dispatch('setGameStatus', 'lose')
              } else if(this.myCards.length > this.opponentCards.length) {
                let idUser = this.user.user.id;
                this.postGameStatus(idUser);
                this.$store.dispatch('setGameStatus', 'win')
              } else {
                this.$store.dispatch('setGameStatus', 'draw')
              }
              this.$router.push('/');
            }, 2000);
          }
        },
        timer(newVal) {
          if(newVal > 30) {
            this.currentRoundCardsKeys = [];
            this.currentRoundCards = [];
            this.playerTurn = !this.playerTurn;
            this.timer = 0;
          }
        }
      },
      beforeDestroy() {
        this.$store.dispatch('setGameValue', false);
        Echo.leave('game-info-users.' + this.getGameId);
      }
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Game.scss";
</style>
