<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Nouvelle zone sous-réseau
			</div>

			<form v-on:submit.stop.prevent="" class="ui form column" v-bind:class="{ 'loading': loading }" id="addnewzonedhcp" style="padding-bottom: 50px">

				<div class="ui stacked secondary">
					<div class="required field">
						<label style="text-align: left">Adresse IP de la zone</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="ipaddresszone" id="ipaddress" v-model="ipaddress" placeholder="IP address">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Début de range</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="startrange" id="startrange" v-model="startrange" placeholder="Début de la range">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Fin de range</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" id="endrange" name="endrange" v-model="endrange" placeholder="Fin de la range">
						</div>
					</div>

				</div>

				<div class="ui divider" style="margin-top: 40px"></div>
				<div class="ui four column stackable centered grid container">
					<div class="left floated column">
						<span class="ui grey button" v-bind:class=" { 'grey': !options, 'violet': options } " @click="showOptions()">Options</span>
					</div>
					<div class="right aligned column">
						<i class="circular inverted teal link icon"
						v-bind:class=" { 'angle double down': !options, 'angle double up': options, 'grey': !options, 'violet': options } " @click="showOptions()"></i>
					</div>
				</div>

				<transition name="bounce">
					<div class="ui grid" v-show="options" style="padding-top: 30px;">

						<div class="sixteen wide column row">
							<div class="required field fourteen wide column">
								<label style="text-align: left">Domain-name-server</label>
								<div class="ui left icon input">
									<i class="server icon"></i>
									<input type="text" name="domainnameserver" id="domainnameserver" v-model="domainnameserver" placeholder="IP address Domain-name-server">
								</div>
							</div>
							<div class="two wide column">
								<i class="add big square green link icon" @click="add_new_domaine_server()" style="font-size: 2.8em; padding-right: 33.59px; margin-top: 20px"></i>
							</div>
						</div>

						<transition-group class="sixteen wide column " name="bounce" tag="div">
							<div class="ui grid" v-for="(domain_name, index) in bonus_domain_name_server" v-bind:key="index">
								<div class="required field fourteen wide column">
									<label style="text-align: left">Domain-name-server - {{ index + 2  }}</label>
									<div class="ui left icon input">
										<i class="server icon"></i>
										<input type="text" v-bind:id="'domain_name_' + index" v-model="bonus_domain_name_server[index]" placeholder="Domain-name-server">
									</div>
								</div>
								<div class="two wide column">
									<i class="minus big square red link icon" @click="removeOneDomainName(index)" style="font-size: 2.8em; padding-right: 33.59px; margin-top: 20px"></i>
								</div>
							</div>
						</transition-group>

						<div class="sixteen wide column row">
							<div class="required field sixteen wide column">
								<label style="text-align: left">Domain name</label>
								<div class="ui left icon input">
									<i class="server icon"></i>
									<input type="text" name="domainename" id="domainename" v-model="domainename" placeholder='"eloryal.fr"'>
								</div>
							</div>
						</div>

					</div>
				</transition>

				<div class="ui divider" style="margin-top: 40px"></div>
				<div class="ui two column stackable centered grid container">
					<div class="left floated column">
						<span class="ui grey button" v-bind:class=" { 'grey': !client_hote, 'brown': client_hote } " @click="showClienthote()">Client hôte</span>
					</div>
					<div class="right aligned column">
						<i class="circular inverted teal link icon"
						v-bind:class=" { 'angle double down': !client_hote, 'angle double up': client_hote, 'grey': !client_hote, 'brown': client_hote } " @click="showClienthote()"></i>
					</div>
				</div>

				<transition name="bounce">
					<div class="ui grid" v-show="client_hote" style="padding-top: 30px;">
						<div class="four column row">
							<h4 class="column" style="margin-top: 10px">
								Client hôte
							</h4>
							<div class="column">
								<span class="ui primary button" @click="">Ajouter</span>
							</div>
							<div class="column"></div>
							<div class="column"></div>

						</div>

						<div class="sixteen wide column">
							<table class="ui single line table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Registration Date</th>
										<th>E-mail address</th>
										<th>Premium Plan</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>John Lilki</td>
										<td>September 14, 2013</td>
										<td>jhlilk22@yahoo.com</td>
										<td>No</td>
									</tr>
									<tr>
										<td>Jamie Harington</td>
										<td>January 11, 2014</td>
										<td>jamieharingonton@yahoo.com</td>
										<td>Yes</td>
									</tr>
									<tr>
										<td>Jill Lewis</td>
										<td>May 11, 2014</td>
										<td>jilsewris22@yahoo.com</td>
										<td>Yes</td>
									</tr>
								</tbody>
							</table>

						</div>



					</div>
				</transition>
				<div class="ui divider" style="margin-top: 40px"></div>

				<div class="ui grid" style="padding-top: 30px;">
					<div class="six column row">
						<div class="column"></div>
						<button class="ui green button" @click="">Save changes</button>
						<div class="column"></div>
						<div class="column"></div>
						<router-link class="ui red button" :to="{name: 'dhcp'}">Cancel</router-link>
						<div class="column"></div>
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

		name: 'Dhcp_AddZone',

		data () {
			return {
				loading: false,
				options: false,
				client_hote: false,
				'ipaddress_form': {
					rules: [{type: 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}' }]
				},
				'startrange_form': {
					rules: [{type: 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}'}]
				},
				'endrange_form': {
					rules: [{type   : 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},

				ipaddress: "192.168.1.0",
				startrange: "192.168.1.42",
				endrange: "192.168.1.200",
				domainnameserver: "192.168.1.251",
				bonus_domain_name_server: ["192.168.1.250"],
				domainename: "\"eloryal.fr\"",
			};
		},

		methods: {
			...Vuex.mapActions(['add_Subnet']),

			initForm() {
				$('#addnewzonedhcp').form('destroy');
				let _this = this;

				var fields = {
					fields: {
						ipaddress: this.ipaddress_form,
						startrange: this.startrange_form,
						endrange: this.endrange_form
					},

					onSuccess(event, field) {
						event.preventDefault();
						_this.addZone();
					}

				};

				if (this.options === true) {
					fields.fields['domainnameserver'] = {
						rules: [{type   : 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
						prompt : 'Veuillez remplir correctement le champs {name}'}]
					};
					fields.fields['domainename'] = {
						rules: [{type   : 'empty', // A APPROFONDIR
						prompt : 'Veuillez remplir correctement le champs {name}'}]
					};
					for (var i = 0; i < this.bonus_domain_name_server.length; i++) {
						fields.fields['domain_name_' + i] = {
							rules: [{type   : 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
							prompt : 'Veuillez remplir correctement le champs {name}'}]
						};
					}
				}
				$('#addnewzonedhcp').form(fields);
			},

			showOptions() {
				this.options = !this.options;
				this.$nextTick(function () {
					this.initForm();
				})
			},

			showClienthote() {
				this.client_hote = !this.client_hote;
				this.$nextTick(function () {
					this.initForm();
				})
			},

			add_new_domaine_server() {
				this.bonus_domain_name_server.push("");
				this.$nextTick(function () {
					this.initForm();
				})

				console.log($('addnewzonedhcp'));
			},

			removeOneDomainName(index) {
				this.bonus_domain_name_server.splice(index, 1)
				this.$nextTick(function () {
					this.initForm();
				})
			},

			addZone() {
				this.loading = true;
				let domain_name_array = [];
				let subnet = {"subnet": this.ipaddress, "netmask": "255.255.255.0",
				"option": {
					"range": {"start": this.startrange, "end": this.endrange },
					"default-lease-time": "600",
					"max-lease-time": "7200",
					"option routers": [] }
				};
				if (this.options) {
					domain_name_array.push(this.domainnameserver);
					this.bonus_domain_name_server.forEach((domain) => {
						domain_name_array.push(domain);
					});
					subnet.option["option domain-name-servers"] = domain_name_array;
					subnet.option["option domain-name"] = this.domainename;
				}

				this.add_Subnet({"server": this.get_servers[this.$route.params.id], "subnet": subnet}).then(response => {
					console.log(response);
					this.loading = false;
					this.$router.push({ name: 'dhcp' });
				}, error => {
					console.log("error addZone - ", error);
					this.loading = false;
				})
				/*this.$http.post('dhcp/subnets/add', {"ip": this.get_servers[this.$route.params.id].ip, "subnet": subnet} ).then((response) => {
					this.loading = false;
					this.$router.push({ name: 'dhcp' });
					console.log(response);					
				},
				(response) => {
					console.log("error addZone - ", response);
					this.loading = false;
				});*/
			}

		},

		computed: {
			...Vuex.mapGetters(['get_servers'])
		},

		mounted: function() {
			this.initForm();

		}
	};
</script>

<style lang="css" scoped>

	.custom_form {
		padding-bottom: 0px;
		padding-top: 0px;
		margin: 0 0 .28571429rem;
		font-size: .92857143em;
		font-weight: 700
	}
	.bounce-enter-active {
		animation: bounce-in .4s;
	}
	.bounce-leave-active {
		animation: bounce-out .4s;
	}
	@keyframes bounce-in {
		0% {
			transform: scale(0);
		}
		50% {
			transform: scale(1.2);
		}
		100% {
			transform: scale(1);
		}
	}
	@keyframes bounce-out {
		0% {
			transform: scale(1);
		}
		50% {
			transform: scale(1.2);
		}
		100% {
			transform: scale(0);
		}
	}
</style>