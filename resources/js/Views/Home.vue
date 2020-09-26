<template>
    <div>
        <h1>home</h1>
        <input type="text" v-model="tbText">
        <button type="button" @click="sendReq">Click me to push</button>
        <ul>
            <li v-for="(m, key) in messages" :key="key">{{ m }}</li>
        </ul>
    </div>
</template>

<script>
    const msgs = [];
    const idCh = Math.floor(Math.random() * 1000);
    import Pusher from 'pusher-js' //ima opcija da se doda da bude enkriptovano sve, pogmedaj na !npm
    Pusher.logToConsole = true;


    var channel = pusher.subscribe(`my-ch-${idCh}`);
    channel.bind('form-submitted', function(data) {
        console.log(data)
      msgs.push(data.text);
    });
    import {pusher} from '../variables.js';
    import axios from 'axios';

    export default {
        name: "Home",
        data() {
            return {
                messages: msgs,
                tbText: '',
                idCh: idCh
            }
        },
        methods: {
            sendReq() {
                if(this.tbText !== '') {
                axios.post('/push', {idCh:this.idCh, text:this.tbText})
                    .then(({ data }) => {
                        console.log('Tu je')
                        this.tbText = ''
                    })
                    .catch(e => {
                        console.log(e, "error sendReq()")
                    })
                } else {
                    console.log('Prazan input');
                }
            }
        }
    }
</script>

<style scoped>

</style>
