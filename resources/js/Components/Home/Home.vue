<template>
    <div>
        <h1>home</h1>
    </div>
</template>

<script>
    import axios from 'axios';
    import {mapGetters} from "vuex";

  export default {
    name: "Home",
    data() {
        return {
        }
    },
    computed: {
      ...mapGetters(['getGameStatus']),
    },
    methods: {

    },
    mounted() {
      switch(this.getGameStatus) {
        case 'win':
          this.$toastr.s("Good job!")
          break;
        case 'opponent-left':
          this.$toastr.s('Oponent left - you win')
          break;
        case 'lose':
          this.$toastr.e('Better luck next time')
          break;
        case 'draw':
          this.$toastr.i('DRAW')
          break;
      }
    }
  }
</script>

<style scoped>

</style>
