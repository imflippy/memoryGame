<template>
    <div class="button-wrapper">
      <button type="button" @click.stop="click">{{value}}</button>
    </div>
</template>

<script>
    export default {
      name: "Button",
      props: {
        value: {
          default: "Click me"
        }
      },
      methods: {
        click() {
          this.$emit('callForm');
        }
      }
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Button.scss";
</style>
