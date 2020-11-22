<template>
  <div>
    <Header v-if="!isGameActive"></Header>
    <slot/>
    <Footer v-if="!isGameActive"></Footer>
  </div>

</template>
<script>
  import Header from "../Components/Header/Header";
  import Footer from "../Components/Footer/Footer";
  import {mapGetters} from "vuex";
  export default {
      name: "Front",
      components: {
        Header,
        Footer
      },
      computed: {
        ...mapGetters(['isGameActive']),
      }
  }
</script>

<style scoped>

</style>
