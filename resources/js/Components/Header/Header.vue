<template>
  <div class="header-wrapper">
    <div class="header">
      <div class="logo">
        <router-link to="/"><img src="/img/logo.png" alt="logo"></router-link>
      </div>
      <Navigation></Navigation>
    </div>

  </div>
</template>

<script>
  import Navigation from "../Navigations/Navigation";

  export default {
    name: "Header",
    components: {
      Navigation
    },
    data() {
      return {

      }
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Header.scss";
</style>
