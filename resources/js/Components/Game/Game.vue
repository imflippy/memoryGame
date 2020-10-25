<template>
  <div class="game-wrapper">
      <h1>Id of route: {{ $route.params.id }}</h1>
  </div>
</template>

<script>
    export default {
        name: "Game"
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Lobby.scss";
</style>
