<template>
  <div v-if="globalLoader" class="global-overlay" :class="size">
    <div class="overlay-loader">
      <div class="loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>
  <div v-else class="overlay-loader">
    <div class="loader">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</template>

<script>
    export default {
      name: "Loader",
      props: {
        globalLoader: {
          type: Boolean,
          default: false
        },
        size: {
          default: ''
        }
      }
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Loader.scss";
</style>

