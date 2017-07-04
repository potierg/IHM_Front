<template>
  <div>
    <div class="ui segment">
      <span>Home</span>

    <div v-for="(t, index) in ttttt">
      -{{ JSON.stringify(t) }}-
      <div @click="tttttt(index)">switch</div>
    </div>
    <div @click="add()">add</div>
      </div>
  </div>
</template>

<script>
  export default {

    name: 'home',
    data () {
      return {
        ttttt: []
      };
    },

    methods: {
      add: function () {
        this.ttttt.push( {targeted: false} );
      },

      tttttt: function(index) {
        this.ttttt[index].targeted = !this.ttttt[index].targeted;
      }
    }

  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .ui.segment {
    background: rgba(0, 0, 0, 0);
    box-shadow: 0 2px 8px 0px rgb(34, 36, 38);
  }
</style>
