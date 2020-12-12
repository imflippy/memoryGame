<template>
  <div class="leader-wrapper" v-if="rows.length">
    <div class="ld-hd">
      <div><h3>Top {{ rows.length }} Players</h3></div>
      <div class="time"><p>Updated at: <span>{{time}}</span></p></div>
    </div>
    <div class="table">
      <table>
        <thead class="header">
        <tr class="row">
          <th class="idRank">Place</th>
          <th class="player">Player</th>
          <th class="wins">Wins</th>
        </tr>
        </thead>
        <tbody class="body">
        <tr class="row" v-for="(r, index) in rows" :key="index">
          <td class="idRank">{{ index + 1 }}</td>
          <td class="player">{{ r.name }}</td>
          <td class="wins">{{ r.wins }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Leaderboard",
    data() {
      return {
        rows: [],
        time: null
      }
    },
    methods: {
      listenToRowsSocket() {
        Echo.channel(`leaderB`)
          .listen('.lb', (data) => {
            this.rows = data.socketData.rows;
            this.time = data.socketData.time;
          });
      },
      pingSocket() {
        axios.get('lb-data').then((res) => {
          console.log('Uspesan ping', res.data)
          this.rows = res.data.rows;
          this.time = res.data.time;
        }).catch((err) => {
          console.log('Eror with pinging', err);
        })
      }
    },
    mounted() {
      this.listenToRowsSocket();
      this.pingSocket();
    },
    beforeDestroy() {
      console.log('LEAVE CH LB')
      Echo.leaveChannel(`leaderB`);
    }
  }
</script>

<style lang="scss">
  @import "./../../../sass/variables.scss";
  @import "./scss/Leaderboard.scss";
</style>
