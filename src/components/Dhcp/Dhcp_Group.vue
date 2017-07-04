<template>
	<div>
		<!-- DHCP GROUP IS USED TO FAKE THE MODIFICATION OF THE DHCP PAGE -->
		<!-- ONLY CHILDREN IS DISPLAY -->
		<!-- THE ONLY GOAL IS TO KEEP CODE CLEAR -->
		<router-view></router-view>
	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../../store'

	export default {

		name: 'Dhcp_Group',

		data () {
			return {

			};
		},

		watch: {				  // when route changes and this component is already rendered
			'$route' (to, from) {
				if (store.getters.get_servers == null || store.getters.get_servers == undefined || to.params.id >= store.getters.get_servers.length || store.getters.get_servers[to.params.id] == null ||
					store.getters.get_servers[to.params.id].service != 'DHCP') {
					console.log("redirected from $route dhcp");
				this.$router.push({name : 'home'});
			}
			else {
			}
		}
	}
};
</script>

<style lang="css" scoped>
</style>