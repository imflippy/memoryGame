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
          this.$toastr.s("V I C T O R Y")
          break;
        case 'opponent-left':
          this.$toastr.s('Opponent left - V I C T O R Y')
          break;
        case 'lose':
          this.$toastr.e('D E F E A T')
          break;
        case 'draw':
          this.$toastr.i('D R A W')
          break;
        case 'expired':
          this.$toastr.e('Your token has expired. Please login again.');
          break;
      }
    }
  }
</script>

<style scoped>

</style>
