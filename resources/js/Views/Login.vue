<template>
    <div>
        <h1>Login</h1>
        <form>
            <input type="email" name="email" v-model="loginForm.email">
            <input type="password" name="password" v-model="loginForm.password">
            <button type="button" @click.stop="loginUser">Login</button>
        </form>
    </div>
</template>

<script>
    import {mapActions} from "vuex";

    export default {
        name: 'Login',
        data () {
            return {
                loginForm: {
                    email: '',
                    password: ''
                }
            }
        },
        methods: {
            async loginUser () {
                try {
                    await this.$store.dispatch('login', this.loginForm);
                    await this.$router.push('About')
                } catch (e) {
                    console.log(e);
                }
            }
        }
    }
</script>
