<template>
    <div class="home-wrapper">
      <section class="first-view-wrapper" style="background-image: url('./img/banner.jpg')">
        <div class="first-view">
          <div class="title">
            <h1>Play online And beat friends</h1>
          </div>
          <div class="phone">
            <img src="../../../../public/img/hero-bg.png" alt="phone">
          </div>
        </div>
      </section>
      <section class="how-it-works">
        <div class="title">
          <h3>How It Works</h3>
        </div>
        <div class="rings">
          <div class="ring-wrapper" v-for="(r, index) in ringsData" :key="index">
            <div class="ring" :class="r.icon">
              <span class="step">{{ index + 1 }}</span>
              <font-awesome-icon :icon="r.icon" />
            </div>
            <div class="ring-title">
                {{ r.title }}
            </div>
          </div>
        </div>
      </section>
    </div>
</template>

<script>
    import axios from 'axios';
    import {mapGetters} from "vuex";

  export default {
    name: "Home",
    data() {
      return {
        ringsData: [
          {
            icon: 'hand-pointer',
            title: 'Signin Account'
          },
          {
            icon: 'gamepad',
            title: 'Play Game'
          },
          {
            icon: 'user-minus',
            title: 'Beat Friend'
          },
          {
            icon: 'trophy',
            title: 'Score Point'
          }
        ]
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

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Home.scss";
</style>
