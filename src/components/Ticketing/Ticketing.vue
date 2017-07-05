<template>
	<div class="ui segment">
		<div class="ui left aligned grid row">
			<div v-bind:class="{ 'active': requestState == false }" class="ui dimmer">
				<div class="ui indeterminate text loader">Fetching data</div>
			</div>

      <div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
        {{serverName}} | TICKETING
      </div>
    </div>
		<a class="ui green button middle aligned" :href="serviceUrl"><h3 style="margin: 1rem;">Accèder à l'IHM du service Ticketing</h3></a>
	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../../store'

	export default {

		name: 'Inventory',

		data () {
			return {
				url: ""
			};
		},

    mounted: function() {
			this.getUrl();
    },

    methods: {

			getUrl() {
				this.requestState = false;
				this.url = "";
				this.$http.post('bugtracker/url', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
					this.url = response.body.response.url;
					this.requestState = true;
				},
				(response) => {
					console.log("error getUrl bugtracker  - ", response);
					this.requestState = true;
				});
				return this.url;
			},
    },

    computed: {
      ...Vuex.mapGetters(['get_servers']),

      serverName: function() {
        this.get_servers[this.$route.params.id].hostname;
        return this.get_servers[this.$route.params.id].hostname;
      },

			serviceUrl: function() {
				return this.url;
			}
    }

  };

</script>

<style lang="css" scoped>
	.target_color {
		background-color: rgba(192,192,192, 0.5);
	}
</style>
