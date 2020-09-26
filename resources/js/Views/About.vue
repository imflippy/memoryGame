<template>
    <div>
        <h1>About</h1>
        <a @click="getUserData">Get User Data</a>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "About",
        methods: {
            getUserData() {
                axios
                    .get('/auth/user-profile')
                    .then(({ data }) => {
                        console.log(data);
                    })
                    .catch(e => {
                        console.log(e)
                    })
            }
        }
    }
</script>

<style scoped>

</style>
