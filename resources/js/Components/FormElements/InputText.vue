<template>
  <div class="input-wrapper">
    <input :type="type"
           :value="value"
           :placeholder="placeholder"
           @input="$emit('update', $event.target.value)"
            class="input-field"/>
    <message :msg-type="msg.msgType" :message="msg.text" :is-visible="checkMessageType"></message>
  </div>
</template>
<script>
  import Message from "../Helpers/Message";
    export default {
      name: "InputText",
      components: {
        'message': Message
      },
      props: {
        type: {
          default: 'text'
        },
        value: {
          type: String
        },
        placeholder: {
          default: "Input text"
        },
        msg: {
          type: Object,
          default: () => {
            return {
              msgType: '',
              text: ''
            }
          }
        }
      },
      model: {
        prop: 'value',
        event: 'update'
      },
      computed: {
        checkMessageType() {
          let possibleTypes = ['info', 'success', 'warning', 'error'];
          return possibleTypes.includes(this.msg.msgType)
        }
      }
    }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/InputText.scss";
</style>
