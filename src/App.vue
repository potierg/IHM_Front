<template>
  <div id="app" class="full-height">

    <div class="ui grid full-height">

      <navbar></navbar>

      <sidebar style="max-width: 290px"></sidebar>

      <div class="center aligned centered twelve wide column">
        <router-view></router-view>

      </div>

    </div>

  </div>
</template>

<script>
  import Sidebar from './components/Sidebar'
  import Navbar from './components/Navbar'
  import Vuex from 'vuex'

  export default {
    components: {
      Sidebar,
      Navbar
    },

    data () {
      return {
      };
    },
    
    methods: {
      ...Vuex.mapActions(['set_LoggedIn'])
    },

    created: function() {
      /*if (localStorage.token === "true") {
        this.set_LoggedIn(true);
      }*/
    },

  computed: {
    ...Vuex.mapGetters(['get_loggedIn'])
  }
}
</script>

<style>
  #app {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial,sans-serif;
    font-size: 1rem;
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }


  .full-height {
    height: 100%;
  }
</style>
