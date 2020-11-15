<template>
  <div class="user-stats-wrapper" :class="newClass">
    <div class="player-stats" v-if="user !== undefined">
      <div class="player">
        <p>User:</p><span>{{user.name}}</span>
      </div>
      <div class="stats">
        <p>Collected:</p>
        <span>{{playerCards}}</span>

      </div>
    </div>
    <div v-else class="waiting">
      <p>Waiting for player...</p>
    </div>
    <div class="is-player-turn">
      <span class="player-turn" v-if="isOnTurn && user !== undefined"><img src="../../../../public/img/brain.png" alt="YOUR TURN"/></span>
    </div>
  </div>
</template>

<script>
  export default {
    name: "UserStats",
    props: {
      user: {
        default: undefined
      },
      newClass: {
        default: ''
      },
      isOnTurn: {
        required: true,
        default: false
      },
      playerCards: {
        default: undefined
      }
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/UserStats.scss";
</style>
