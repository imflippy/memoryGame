<template>
    <div>
        <page-info title="Login"></page-info>
        <div class="login-form">
          <form>
            <form-title title="Login and Play!"></form-title>
            <input-text v-model.trim="$v.email.$model" placeholder="Email" :msg="emailError" @update="delayTouch($v.email)"></input-text>
            <input-text v-model.trim="$v.password.$model" type="password" placeholder="Password" :msg="passwordError" @update="delayTouch($v.password)"></input-text>

            <custom-button @callForm="loginUser" value="Login" class="btn-login" v-if="!loader"></custom-button>
            <loader v-else></loader>
            <message msg-type="error" :message="getLoginMessage" :is-visible="getLoginMessage !== ''"></message>
          </form>
          <div class="quick-links">
            <router-link to="/register" class="nav-link">Create new account</router-link>
<!--            <router-link to="/register" class="nav-link">Forget Password?</router-link>-->
          </div>
        </div>

    </div>
</template>

<script>
  import { required, email, helpers, minLength} from 'vuelidate/lib/validators';
  const regexPassword = helpers.regex('regexPassword', /^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/);
  const touchMap = new WeakMap();

  import {mapActions, mapGetters} from "vuex";
  import PageInfo from '../PageInfo/PageInfo';
  import InputText from '../FormElements/InputText';
  import Button from '../FormElements/Button';
  import FormTitle from "../FormElements/FormTitle";
  import Message from "../Helpers/Message";
  import Loader from "../Helpers/Loader";
  import axios from "axios";

  export default {
    name: 'Login',
    components: {
      'page-info': PageInfo,
      'input-text': InputText,
      'custom-button': Button,
      'form-title': FormTitle,
      'message': Message,
      'loader': Loader
    },
    data () {
      return {
        email: '',
        password: '',
        loader: false
      }
    },
    validations: {
      email: {
        required, email
      },
      password: {
        required,
        minLength: minLength(6),
        regexPassword
      }
    },
    methods: {
      async loginUser () {
        try {
          this.$v.$touch()
          if(!this.$v.$invalid) {
            this.loader = true;
            let loginForm = {
              email: this.email,
              password: this.password
            }
            await this.$store.dispatch('login', loginForm);

            if(this.getLoginMessage === '') {
              await this.$store.dispatch('getOnlineUsersFromDB');
              await this.$router.push('/')
            }
            this.loader = false;

          }
        } catch (e) {
          console.log(e);
        }
      },
      delayTouch($v) {
        $v.$reset()
        if (touchMap.has($v)) {
          clearTimeout(touchMap.get($v))
        }
        touchMap.set($v, setTimeout($v.$touch, 1000))
      }
    },
    computed: {
      ...mapGetters(['getLoginMessage', 'user']),
      emailError() {
        if(this.$v.email.$dirty && !this.$v.email.required) {
          let msg = {
            text: "Email is required",
            msgType: 'warning'
          }
          return msg;
        }
        if(this.$v.email.$dirty && !this.$v.email.email) {
          let msg = {
            text: "Please enter your email in format yourname@example.com",
            msgType: 'warning'
          }
          return msg;
        }
      },
      passwordError() {
        if(this.$v.password.$dirty && !this.$v.password.required) {
          let msg = {
            text: "Password is required",
            msgType: 'warning'
          }
          return msg;
        }
        if(this.$v.password.$dirty && !this.$v.password.minLength) {
          let msg = {
            text: "Password length must be 6 or longer",
            msgType: 'warning'
          }
          return msg;
        }
        if(this.$v.password.$dirty && !this.$v.password.regexPassword) {
          let msg = {
            text: "Password = number, lowercase, uppercase character and one of those (!, $, #, %)",
            msgType: 'warning'
          }
          return msg;
        }
      }
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Login.scss";
</style>
