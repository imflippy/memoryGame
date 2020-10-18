<template>
  <section class="page-info" style="background-image: url('./img/banner.jpg')">
    <h2>{{ title }}</h2>
  </section>
</template>

<script>
  export default {
    name: "PageInfo",
    props: {
      title: {
        type: String,
        default: 'Welcome'
      }
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/PageInfo.scss";
</style>
