<template>
  <div :class="msgType" v-if="isVisible"><p>{{ message }}</p></div>
</template>

<script>
  export default {
    name: "Message",
    props: {
      msgType: {
        type: String,
        default: 'info'
      },
      isVisible: {
        type: Boolean,
        default: true
      },
      message: {
        type: String,
        required: true
      }
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Message.scss";
</style>
