<template>
  <div class="ui segment">

    <div v-bind:class="{ 'active': requestState == false }" class="ui dimmer">
      <div class="ui indeterminate text loader">Preparing Files</div>
    </div>
    <p></p>

    <h1 style="padding-top: 20px; padding-bottom: 40px">
      Update {{get_servers[$route.params.id].hostname}}
    </h1>
    <table class="ui sortable celled table" id="tableInterface">
      <thead>
        <tr>
          <th class="sorted descending">Interface name</th>
          <th class="">Status</th>
          <th>IP address</th>
          <th>More info</th>
          <th>Reboot</th>
          <th>Boot</th>
          <th>Shutdown</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(interface, index) in interfaces">
          <td v-bind:class="{ 'warning': interface.name.length == 0, 'positive': interface.name.length > 0}">{{interface.name}}</td>
          <td v-bind:class="{ 'negative': interface.status !== 'UP',  'positive': interface.status === 'UP'}">{{interface.status === 'UNKNOWN' ? 'DOWN' : 'UP' }}</td>
          <td v-bind:class="{ 'warning': interface.ip.length == 0, 'positive': interface.ip.length > 0}">{{interface.ip || 'none'}}</td>
          <td @click="detailsInterface(index, interface.name)">More info</td>
          <td @click="rebootInterface(index, interface.name)">Reboot Interface</td>
          <td @click="bootInterface(index, interface.name)">Boot</td>
          <td @click="shutdown(index, interface.name)">Shutdown</td>
        </tr>
      </tbody>
      <tfoot>
        <tr><th>{{interfaces.length}} Interfaces</th>
          <th>{{getNumberStatusUp.length}} Available</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr></tfoot>
      </table>
    </div>
  </template>

  <script>

    import Vuex from 'vuex'
    import store from '../store'

    export default {

      name: 'Network',

      data () {
        return {
          interfaces: [],
          requestState: false
        };
      },

      mounted: function () {
        this.requestState = false;
        $('#tableInterface').tablesort();
        this.getInterface();
      },

      methods: {
        getInterface() {
          this.requestState = false;
          this.interfaces = [];
          this.$http.post('config/interfaces', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
            this.interfaces = response.body.response;
            this.requestState = true;
          },
          (response) => {
            console.log("error getInterface - ", response);
            this.requestState = true;
          });
        },

        detailsInterface(index, nameInterface) {
          this.requestState = false;
          this.$http.post('config/interfaces/details', {"ip": this.get_servers[this.$route.params.id].ip, "name": nameInterface } ).then((response) => {
            this.requestState = true;
          },
          (response) => {
            console.log("error detailsInterface - ", response);
            this.requestState = true;
          });
        },

        rebootInterface(index, nameInterface) {
          this.requestState = false;
          this.$http.post('config/interfaces/reboot', {"ip": this.get_servers[this.$route.params.id].ip, "name": nameInterface } ).then((response) => {
            this.requestState = true;
          },
          (response) => {
            console.log("error rebootInterface - ", response);
            this.requestState = true;
          });
        },

        bootInterface(index, nameInterface) {
          this.requestState = false;
          this.$http.post('config/interfaces/powerup', {"ip": this.get_servers[this.$route.params.id].ip, "name": nameInterface } ).then((response) => {
            this.requestState = true;
          },
          (response) => {
            console.log("error bootInterface - ", response);
            this.requestState = true;
          });
        },

        shutdown(index, nameInterface) {
          this.requestState = false;
          this.$http.post('config/interfaces/poweroff', {"ip": this.get_servers[this.$route.params.id].ip, "name": nameInterface } ).then((response) => {
            this.requestState = true;
          },
          (response) => {
            console.log("error shutdown - ", response);
            this.requestState = true;
          });
        }
      },

      computed: {
        ...Vuex.mapGetters(['get_servers']),

        getServerIP: function () {
          return this.get_servers[this.$route.params.id].ip;
        },

        getNumberStatusUp: function() {
          return this.interfaces.filter(inter => {
            if (inter.status === 'UP')
              return inter;
          });
        }
      },

  watch: {          // when route changes and this component is already rendered
    '$route' (to, from) {
      if (store.getters.get_servers == null || to.params.id >= store.getters.get_servers.length || store.getters.get_servers[to.params.id] == null)
        this.$router.push({name : 'home'});
      else  {
        this.getInterface();
      }
    }
  }

};
</script>

<style lang="css" scoped>
</style>