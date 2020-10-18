<template>
  <div>
    <Header></Header>
    <slot/>
    <Footer></Footer>
  </div>

</template>
<script>
    import Header from "../Components/Header/Header";
    import Footer from "../Components/Footer/Footer";
    export default {
        name: "Front",
        components: {
          Header,
          Footer
        }
    }
</script>

<style scoped>

</style>
