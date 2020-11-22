<template>
  <div class="footer-wrapper">
    <footer>
      <div class="footer-top">
        <div class="logo-footer">
          <router-link to="/"><img src="/img/logo.png" alt="logo"></router-link>
        </div>
        <Navigation></Navigation>
      </div>
      <div class="footer-bot">
        <p>Copyright © 2020. All Rights Reserved by <a href="https://www.filipminic.com" target="_blank">filipminic.com</a></p>
      </div>
    </footer>
  </div>
</template>

<script>
  import Navigation from "../Navigations/Navigation";
    export default {
        name: "Footer",
      components: {
          Navigation
      }
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Footer.scss";
</style>
