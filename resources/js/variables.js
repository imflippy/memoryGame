import Pusher from "pusher-js";

export const myVar = 'This is my var';
export const pusher = new Pusher('fbaa4e2714086ca20608', {
    cluster: 'eu'
})

export const navigationLinks = {
  'home': {
    'name': 'Home',
    'href': '/',
    'auth': false
  },
  'login': {
    'name': 'Login',
    'href': '/login',
    'auth': false
  },
  'register': {
    'name': 'Register',
    'href': '/register',
    'auth': false
  },
  'game': {
    'name': 'Play Game',
    'href': '/game',
    'auth': true
  }
};
// export default  {
//     myVar: "this is var my",
//     pusher: new Pusher('fbaa4e2714086ca20608', {
//         cluster: 'eu'
//     })
// }
