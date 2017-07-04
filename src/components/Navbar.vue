<template>
  <div class="sixteen wide column" style="padding-bottom: 0px; margin: 0px">
    <div class="ui inverted blue stackable menu" style="border-radius: 0;">
      <div class="right menu">
        <!-- <router-link :to="{name: 'log in'}" class="item" v-if="!get_loggedIn">Log in</router-link> -->
        <!-- <router-link :to="{name: 'sign up'}" class="item" v-if="!get_loggedIn">Sign up</router-link> -->
        <!-- <router-link :to="{name: 'log out'}" class="item" v-if="get_loggedIn">Logout</router-link> -->
        <a class="item">
          <i class="search icon"></i>Help
        </a>
      </div>
    </div>
  </div>
</template>

<script>
  import store from '../store'
  import Vuex from 'vuex'

  export default {

    name: 'Navbar',

    store: store,

    data () {
      return {
      };
    },

    mounted: function() {
    },

    created: function() {
    },

    computed: {
      ...Vuex.mapGetters(['get_loggedIn'])
    }
  };
</script>

<style lang="css" scoped>
</style>"