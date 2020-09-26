import Pusher from "pusher-js";

export const myVar = 'This is my var';
export const pusher = new Pusher('fbaa4e2714086ca20608', {
    cluster: 'eu'
})

// export default  {
//     myVar: "this is var my",
//     pusher: new Pusher('fbaa4e2714086ca20608', {
//         cluster: 'eu'
//     })
// }
