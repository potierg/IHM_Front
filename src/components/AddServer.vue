<template>
	<div class="ui center aligned grid" style="padding-top: 80px">
		<div class="column">
			<h2 class="ui image header">
				<div class="content">
					Please fill the form to add a new server
				</div>
			</h2>
			<form v-on:submit.stop.prevent="addServer" class="ui form" id="newserver">
				<div class="ui stacked secondary segment">

					<div class="required field">
						<label style="text-align: left">Service</label>
						<select class="ui fluid search dropdown" tabindex="0" name="service_server" id="service_server_addserver" v-model="service_server">
							<option value="none">No service</option>
							<option value="dhcp">DHCP service</option>
							<option value="dns">DNS service</option>
							<option value="sftp">SFTP service</option>
							<option value="supervision">Supervision service</option>
							<option value="maj">MAJ service</option>

						</select>
					</div>
					<div class="two fields">
						<div class="required field">
							<label style="text-align: left">IP address</label>
							<div class="ui left icon input">
								<i class="server icon"></i>
								<input type="text" name="ipaddress" id="ipaddress" v-model="ipaddress" placeholder="IP address">
							</div>
						</div>
						<div class="required field">
							<label style="text-align: left">Hostname</label>
							<div class="ui left icon input">
								<i class="user icon"></i>
								<input type="text" name="hostname" id="hostname" v-model="hostname" placeholder="Hostname">
							</div>
						</div>
					</div>
					<button type="submit" class="ui fluid large teal button" id="confirm_add_server">Confirm</button>

				</div>
				<div class="ui error message"></div>

			</form>
		</div>
		
	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../store'

	export default {

		name: 'AddServer',

		data () {
			return {
				service_server: "",
				ipaddress: "",
				hostname: ""
			};
		},

		methods: {

			addServer() {
				if ($('#newserver').form("is valid")) {
					$("#confirm_add_server").toggleClass( "loading" );

					this.$http.post('servers/add', {"service" : this.service_server, "name": this.hostname, "ip" : this.ipaddress} ).then((response) => {
						$("#confirm_add_server").toggleClass( "loading" );

						store.commit('ADD_SERVER', {"id": this.get_servers.length, "hostname": this.hostname, "service": this.service_server.toUpperCase(), "ip": this.ipaddress});

						this.$router.push({ name: 'settings', params : {id: this.get_servers.length - 1 } });

					},
					(response) => {
						console.log(response);
						$("#confirm_add_server").toggleClass( "loading" );
						console.log("error add server");
					});
				}
			}
		},

		computed: {
			...Vuex.mapGetters(['get_servers']),
		},

		mounted: function() {
			$('#service_server_addserver').dropdown();

			$('#newserver').form({
				fields: {
					service_server: {
						rules: [
						{
							type   : 'empty',
							prompt : '{name} must have a value'
						}
						]
					},
					ipaddress: {
						rules: [
						{
							type   : 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
							prompt : 'Please type a correct {name}'
						}
						]
					},
					hostname: {
						rules: [
						{
							type   : 'minLength[2]',
							prompt : '{name} must have at least {ruleValue} characters'
						}
						]
					},
				},

				onSuccess(event, field) {
					event.preventDefault();
				}
			})
		}
	};
</script>

<style lang="css" scoped>
	.column {
		max-width: 600px;
	}
</style>