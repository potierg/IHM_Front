<template>
	<div class="ui segment">
		<div v-bind:class="{ 'active': requestState == false }" class="ui dimmer">
			<div class="ui indeterminate text loader">Fetching data</div>
		</div>
		<p></p>

		<div class="ui grid" style="padding-bottom: 50px; padding-top: 25px">
			<div class="two wide column">
			</div>
			<div v-if="serverState == 'on'" class="four wide column">
				<i @click="showModalRebootServer" class="inverted teal huge circular refresh icon link"></i>	<!-- AVAILABLE WHEN SERVER IS ON ONLY -->
			</div>
			<div class="four wide column">
				<i @click="showModalOnOffServer" v-bind:class="{red: serverState == 'on',  green: serverState == 'off'}" class="inverted huge circular power icon link"></i>		<!-- COLOR ACCORDING TO THE SERVER ON/OFF STATE -->
			</div>
			<div class="four wide column">
				<i @click="showModalDeleteServer" class="inverted huge circular trash icon link"></i>
			</div>
		</div>
		<div class="ui horizontal divider">
			Or
		</div>
		<h2 class="ui image header">
			<div class="content">
				Update {{serverName || 'unknown'}}
			</div>
		</h2>
		<div class="ui center aligned grid">
			<form v-on:submit.stop.prevent="" v-bind:class="{ 'loading': loading }" class="ui form column" id="updateServer" style="padding-top: 50px; max-width: 600px;">
				<div class="ui stacked secondary segment">
					<div class="required field">
						<label style="text-align: left">Service</label>
						<select class="ui fluid search dropdown" name="service_server" id="service_server_settings" v-model="service_server">
							<option v-for="option in options" v-bind:value="option.value">
								{{option.text}}
							</option>
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
					<button type="submit" class="ui fluid large teal button">Confirm</button>

				</div>
				<div class="ui error message"></div>

			</form>
		</div>
		<div class="ui basic modal" id="deleteServer">
			<div class="ui icon header">
				<i class="archive icon"></i>
				<p>Remove {{hostname || 'unknown' }}</p>
			</div>
			<div class="content">
				<p>Your inbox is getting fulzerzerzel, would you like us to enable automatic archiving of old messages?</p>
			</div>
			<div class="actions">
				<div class="ui red basic cancel inverted button">
					<i class="remove icon"></i>
					No
				</div>
				<div class="ui green ok inverted button">
					<i class="checkmark icon"></i>
					Yes
				</div>
			</div>
		</div>
		<div class="ui basic modal" id="OnOffServer">
			<div class="ui icon header">
				<i class="archive icon"></i>
				Eteindre {{serverName || 'unknown'}}
			</div>
			<div class="content">
				<p>Your inbox is getting full, would you like us to enable automatic archiving of old messages?</p>
			</div>
			<div class="actions">
				<div class="ui red basic cancel inverted button">
					<i class="remove icon"></i>
					No
				</div>
				<div class="ui green ok inverted button">
					<i class="checkmark icon"></i>
					Yes
				</div>
			</div>
		</div>
		<div class="ui basic modal" id="rebootServer">
			<div class="ui icon header">
				<i class="archive icon"></i>
				Reboot {{serverName || 'unknown'}}
			</div>
			<div class="content">
				<p>Your inbox is getting full, would you like us to enable automatic archiving of old messages?</p>
			</div>
			<div class="actions">
				<div class="ui red basic cancel inverted button">
					<i class="remove icon"></i>
					No
				</div>
				<div class="ui green ok inverted button">
					<i class="checkmark icon"></i>
					Yes
				</div>
			</div>
		</div>
	</div>

</template>

<script>
	import Vuex from 'vuex'
	import store from '../store'

	export default {

		name: 'Settings',

		data () {
			return {
				service_server: "none",
				ipaddress: "",
				hostname: "",
				options: [
				{text: 'No service', value: 'none'},
				{text: 'DHCP service', value: 'dhcp'},
				{text: 'DNS service', value: 'dns'},
				{text: 'SFTP service', value: 'sftp'},
				{text: 'Supervision service', value: 'supervision'},
				{text: 'MAJ service', value: 'maj'},
				{text: 'Web service', value: 'web'},
				{text: 'Ticketing service', value: 'ticket'},
				{text: 'Proxy Web service', value: 'proxy'},
				{text: 'Ticketing service', value: 'ticket'}
				],
				requestState: false,
				serverState: 'unknown',
				loading: false
			};
		},

		methods: {
			...Vuex.mapActions(['delete_Server', 'set_Server']),

			updateServer() {
				console.log(this.get_servers[this.$route.params.id].ip, this.service_server, this.hostname, this.ipaddress);
				this.loading = true;
				this.$http.post('servers/update', {"origin_ip": this.get_servers[this.$route.params.id].ip, "service": this.service_server, "name": this.hostname, "ip": this.ipaddress} ).then((response) => {

					this.set_Server({"service": this.service_server, "hostname": this.hostname, "ip": this.ipaddress, "idx": this.$route.params.id});
					this.loading = false;

					this.$router.push({ name: 'home' });
				},
				(response, tt) => {
					console.log("error rebootServer - ", response);
					this.loading = false;

				});
			},

			showModalDeleteServer() {
				$('#deleteServer').modal('show');
			},

			showModalRebootServer() {
				$('#rebootServer').modal('show');
			},

			showModalOnOffServer() {
				$('#OnOffServer').modal('show');
			},

			deleteServer() {
				this.$http.post('servers/delete', {"ip": this.get_servers[this.$route.params.id].ip} ).then((response) => {
					$('#accordion_sidebar_servers').accordion("close", this.$route.params.id);
					this.delete_Server(this.get_servers[this.$route.params.id]).then(response => {
						this.$router.push({ name: 'home' });
					});
				},
				(response) => {
					console.log("error deleteServer - ", response);
				});
			},

			rebootServer() {
				this.$http.post('config/reboot', {"ip": this.get_servers[this.$route.params.id].ip} ).then((response) => {
					console.log(response.body);
					this.$router.push({ name: 'home' });
				},
				(response) => {
					console.log("error rebootServer - ", response);
				});
			},

			OnOffServer(serverState) {
				if (this.serverState == 'on') {
					console.log("ici");
					this.$http.post('config/poweroff', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
						console.log(response);
						if (response.body == 'OK')
							this.serverState = 'off';
					},
					(response) => {
						console.log("error switchServerState - ", response);
					});
				}
			},

			initForm() {
				let _this = this;
				$('#service_server_settings').dropdown();

				$('#deleteServer').modal({
					blurring: true,
					observeChanges: true,
					onDeny    : function(){
					},
					onApprove : function() {
						_this.deleteServer();
					}
				});

				$('#rebootServer').modal({
					blurring: true,
					onDeny    : function(){
					},
					onApprove : function() {
						_this.rebootServer();
					}
				});

				$('#OnOffServer').modal({
					blurring: true,
					onDeny    : function(){
					},
					onApprove : function() {
						_this.OnOffServer();
					}
				});


				this.service_server = this.get_servers[this.$route.params.id].service.toLowerCase();
				this.ipaddress = this.get_servers[this.$route.params.id].ip;
				this.hostname = this.get_servers[this.$route.params.id].hostname;
				$('#service_server_settings').dropdown('set value', this.service_server);

				$('#updateServer').form({
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
					_this.updateServer();
				}
			})
			},

			requestServerState() {
				this.$http.post('config/is_on', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
					this.requestState = true;
					if (response.body.response)
						this.serverState = 'on';
					else
						this.serverState = 'off';
				},
				(response) => {
					console.log("error requestServerState - ", response);
					this.requestState = true;
					this.serverState = 'unknown';
				});
			}
		},

		computed: {
			...Vuex.mapGetters(['get_servers']),

			serverName: function() {
				//this.get_servers[this.$route.params.id].hostname;
				return (this.$route.params.id < this.get_servers.length && this.get_servers[this.$route.params.id].hasOwnProperty("hostname")) ? this.get_servers[this.$route.params.id].hostname : 'unknown';
			}
		},

		mounted: function() {
			this.requestState = false;
			this.serverState = 'unknown';
			this.initForm();
			this.requestServerState();
		},

  watch: {				  // when route changes and this component is already rendered
  	'$route' (to, from) {
  		if (store.getters.get_servers == null || to.params.id > store.getters.get_servers.length || store.getters.get_servers[to.params.id] == null)
  			this.$router.push({name : 'home'});
  		else {
  			this.requestState = false;
  			this.serverState = 'unknown';
  			this.initForm();
  			this.requestServerState();
  		}
  	}
  },

  beforeRouteLeave (to, from, next) {
  	if ($('.ui.dimmer.modals.page').length) {
  		$('.ui.dimmer.modals.page').remove();
  	}
  	next();
  }
};
</script>

<style lang="css" scoped>
</style>
