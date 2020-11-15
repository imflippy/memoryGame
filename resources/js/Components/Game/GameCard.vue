<template>
  <div class="game-card-wrapper" @click="openCard">
    <div class="game-card"
        :class="[
          isOpened ? 'opened' : '',
          isMyCard ? 'my-card' : '',
          isOppoentCard ? 'opponent-card' : ''
          ]">
      <div class="front" >{{card.id}}</div>
      <div class="back">Yo, what up?</div>
    </div>
  </div>
</template>

<script>

  import {mapGetters} from "vuex";
  export default {
    name: "GameCard",
    props: {
      card: {
        type: Object,
        required: true
      },
      positionId: {
        type: Number,
        required: true
      },
      isOpened: {
        type: Boolean,
        default: false
      },
      isMyCard: {
        type: Boolean,
        default: false
      },
      isOppoentCard: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
      }
    },
    computed: {
      ...mapGetters(['user', 'getOnlineUsers'])
    },
    methods: {
      openCard() {
        if(!this.isOpened) {
          let cardDetails = {
            cardId: this.card.id,
            positionId: this.positionId
          }
          this.$emit('openCard', cardDetails)
        }
      }
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/GameCard.scss";
</style>
