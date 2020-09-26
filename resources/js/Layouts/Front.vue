<template>
    <div>
        <Navigation></Navigation>
        <h1>Front</h1>
        <slot/>
    </div>

</template>

<script>
    import Navigation from "../Components/Navigation";
    export default {
        name: "Front",
        components: {
            Navigation
        }
    }
</script>

<style scoped>

</style>
