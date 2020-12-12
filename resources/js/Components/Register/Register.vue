<template>
  <div class="register-wrapper">
    <page-info title="Register"></page-info>
    <div class="register-form">
      <form @keyup.enter="registerUser">
        <form-title title="Create account"></form-title>
        <div class="form-inputs">
          <div class="form-inputs-left">
            <input-text v-model.trim="$v.email.$model" placeholder="Email" :msg="emailError" @update="delayTouch($v.email)"></input-text>
            <input-text v-model.trim="$v.username.$model" placeholder="Username" :msg="usernameError" @update="delayTouch($v.username)"></input-text>
          </div>
          <div class="form-inputs-right">
            <input-text v-model.trim="$v.password.$model" type="password" placeholder="Password" :msg="passwordError" @update="delayTouch($v.password)"></input-text>
            <input-text v-model.trim="$v.confirmPassword.$model" type="password" placeholder="Re-Password" :msg="confirmPasswordError" @update="delayTouch($v.confirmPassword)"></input-text>
          </div>
        </div>

        <custom-button @callForm="registerUser" value="Register now" class="btn-register" v-if="!loader"></custom-button>
        <loader v-else></loader>
        <message msg-type="error" :message="getRegisterMessage" :is-visible="getRegisterMessage !== ''"></message>
      </form>
      <div class="quick-links">
        <router-link to="/login" class="nav-link">Login account?</router-link>
      </div>
    </div>
  </div>
</template>

<script>
  import { required, email, helpers, minLength, sameAs} from 'vuelidate/lib/validators';
  const regexPassword = helpers.regex('regexPassword', /^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/);
  const touchMap = new WeakMap();

  import {mapActions, mapGetters} from "vuex";
  import PageInfo from '../PageInfo/PageInfo';
  import InputText from '../FormElements/InputText';
  import Button from '../FormElements/Button';
  import FormTitle from "../FormElements/FormTitle";
  import Message from "../Helpers/Message";
  import Loader from "../Helpers/Loader";

  export default {
    name: "Register",
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
        confirmPassword: '',
        username: '',
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
      },
      confirmPassword: {
        required,
        sameAsPassword: sameAs('password')
      },
      username: {
        required
      }
    },
    methods: {
      async registerUser () {
        try {
          this.$v.$touch()
          if(!this.$v.$invalid) {
            this.loader = true;
            let registerForm = {
              email: this.email,
              name: this.username,
              password: this.password,
              password_confirmation: this.confirmPassword
            }
            await this.$store.dispatch('register', registerForm);
            await this.$store.dispatch('login', registerForm);
            if(this.getRegisterMessage === '') {
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
      ...mapGetters(['getRegisterMessage']),
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
      },
      confirmPasswordError() {
        if(this.$v.confirmPassword.$dirty && !this.$v.confirmPassword.required) {
          let msg = {
            text: "Re-Password is required",
            msgType: 'warning'
          }
          return msg;
        }
        if(this.$v.confirmPassword.$dirty && !this.$v.confirmPassword.sameAsPassword) {
          let msg = {
            text: "Re-Password must be same as Password",
            msgType: 'warning'
          }
          return msg;
        }
      },
      usernameError() {
        if(this.$v.username.$dirty && !this.$v.username.required) {
          let msg = {
            text: "Username is required",
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
  @import "./scss/Register.scss";
</style>
