<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Nouvelle Host Client
			</div>

			<form v-on:submit.stop.prevent="" class="ui form column" v-bind:class="{ 'loading': loading }" id="addnewhostdhcp" style="padding-bottom: 50px">

				<div class="ui stacked secondary">
					<div class="required field">
						<label style="text-align: left">Name</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="namehost" id="name" v-model="name" placeholder="Name">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Hardware Ethernet</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="hardethernethost" id="hardethernet" v-model="hardethernet" placeholder="Hardware Ethernet">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Ip Fixe</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" id="ipfixehost" name="ipfixe" v-model="ipfixe" placeholder="Ip Fixe">
						</div>
					</div>

				</div>

				<div class="ui divider" style="margin-top: 40px"></div>

				<div class="ui grid" style="padding-top: 30px;">
					<div class="six column row">
						<div class="column"></div>
						<button class="ui green button" @click="">Save changes</button>
						<div class="column"></div>
						<div class="column"></div>
						<router-link class="ui red button" :to="{name: 'dhcp.editzone', params: {id: $route.params.id}}">Cancel</router-link>
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

		name: 'Dhcp_AddHost',

		data () {
			return {
				loading: false,
				'name_form': {
					rules: [{type   : 'empty',
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},
				'hardethernet_form': {
					rules: [{type: 'regExp[^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$]', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}'}]
				},
				'ipfixe_form': {
					rules: [{type   : 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},

				name: "",
				hardethernet: "",
				ipfixe: "",
			};
		},

		methods: {

			initForm() {
				$('#addnewzonedhcp').form('destroy');
				let _this = this;

				console.log(this.$route.params.subnet);

				var fields = {
					fields: {
						name: this.name_form,
						hardethernet: this.hardethernet_form,
						ipfixe: this.ipfixe_form
					},

					onSuccess(event, field) {
						event.preventDefault();
						_this.addHost();
					}

				};

				$('#addnewhostdhcp').form(fields);
			},

			addHost() {
				this.loading = true;

				let host_conf = {"name":this.name, "hardware ethernet":this.hardethernet, "fixed-address":this.ipfixe};

				this.$http.post('dhcp/subnets/hc/add', {"ip": this.get_servers[this.$route.params.id].ip, "subnet_ip": this.$route.params.subnet_ip, "host-client": host_conf} ).then((response) => {
					this.loading = false;
					this.$router.push({ name: 'dhcp.editzone', params: {"id": this.$route.params.id}});
				});
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