<template>
  <div class="from-title-wrapper">
    <h3>{{ title }}</h3>
  </div>
</template>

<script>
    export default {
      name: "FormTitle",
      props: {
        title: {
          required: true,
        }
      }
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/FormTitle.scss";
</style>

