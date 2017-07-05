<template>
	<div class="ui segment">
		<div class="ui left aligned grid row">
			<div v-bind:class="{ 'active': requestState == false }" class="ui dimmer">
				<div class="ui indeterminate text loader">Fetching data</div>
			</div>

      <div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
        {{serverName}} | SUPERVISION
      </div>
    </div>
		<a class="ui green button middle aligned" :href="serviceUrl"><h3 style="margin: 1rem;">Accèder à l'IHM du service de Supervision</h3></a>

		<div class="ui left aligned">
			<div class="ui grid" style="padding-bottom: 20px; padding-top: 50px;">
				<div class="left aligned four wide column">
					<h2>Installation des agents</h2>
				</div>
			</div>
			<form v-on:submit.stop.prevent="installingAgent" v-bind:class="{ 'loading': loading }" class="ui form column" id="installAgent" style="padding-top: 10px; max-width: 600px;">
				<div class="ui stacked secondary segment">
					<div class="two fields">
						<div class="required field">
							<label style="text-align: left">IP address</label>
							<div class="ui left icon input">
								<i class="server icon"></i>
								<input type="text" name="ipaddress" id="ipaddress" v-model="ipaddress" placeholder="IP address">
							</div>
						</div>
						<div class="field" style="padding-top: 20px;">
							<button type="submit" class="ui fluid large teal button" id="confirm_add_agent">Confirm</button>
						</div>
					</div>
				</div>
				<div class="ui error message"></div>
			</form>
		</div>
	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../../store'

	export default {

		name: 'Inventory',

		data () {
			return {
				url: "",
				ipaddress: ""
			};
		},

    mounted: function() {
			this.getUrl();

			$('#installAgent').form({
				fields: {
					ipaddress: {
						rules: [
							{
								type   : 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
								prompt : 'Please type a correct {name}'
							}
						]
					},
				},
				onSuccess(event, field) {
					event.preventDefault();
				}
			})

    },

    methods: {

			installingAgent() {
				if ($('#installAgent').form("is valid")) {
					$("#confirm_add_agent").toggleClass( "loading" );

					this.$http.post('supervision/agent/add', {"ip_server" : this.get_servers[this.$route.params.id].ip, "ip": this.ipaddress} ).then((response) => {
						$("#confirm_add_agent").toggleClass( "loading" );
					},
					(response) => {
						console.log(response);
						$("#confirm_add_agent").toggleClass( "loading" );
						console.log("error add server");
					});
				}
			},

			getUrl() {
				this.requestState = false;
				this.url = "";
				this.$http.post('supervision/get_url', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
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
