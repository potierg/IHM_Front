<template>
</template>

<script>
  import store from '../store'

export default {

  name: 'Logout',

	store: store,

  data () {
    return {

    };
  },

  beforeRouteEnter (to, from, next) {
    store.commit('SET_LOGGEDIN', false);
    next('/login')
  }
};
</script>

<style lang="css" scoped>
</style>