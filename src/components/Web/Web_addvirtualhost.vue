addnewvirtualhost<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Nouveau VirtualHost
			</div>

			<form v-on:submit.stop.prevent="" class="ui form column" v-bind:class="{ 'loading': loading }" id="addnewvirtualhost" style="padding-bottom: 50px">

				<div class="ui stacked secondary">
					<div class="required field">
						<label style="text-align: left">Adresse IP du serveur</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="ipaddresszone" id="ipaddress" v-model="ipaddress" placeholder="IP address">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Nom du serveur</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="server_name" id="server_name" v-model="server_name" placeholder="Nom du pool">
						</div>
					</div>
				</div>

				<div class="ui grid" style="padding-top: 30px;">
					<div class="six column row">
						<div class="column"></div>
						<button class="ui green button" @click="">Save changes</button>
						<div class="column"></div>
						<div class="column"></div>
						<router-link class="ui red button" :to="{name: 'web'}">Cancel</router-link>
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

		name: 'Proxy_addVirtualhost',

		data () {
			return {
				loading: false,
				'ipaddress_form': {
					rules: [
            {
              type: 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
					    prompt: 'Veuillez remplir correctement le champs {name}'
            }
          ]
				},
				'server_name_form': {
          rules: [{type   : 'empty', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}'}]
				},
				ipaddress: "",
				server_name: "",
			};
		},

		methods: {

			initForm() {
				$('#addnewvirtualhost').form('destroy');
				let _this = this;

				var fields = {
					fields: {
						ipaddress: this.ipaddress_form,
						server_name: this.server_name_form
					},

					onSuccess(event, field) {
						event.preventDefault();
						_this.addVirtualhost();
					}

				};
				$('#addnewvirtualhost').form(fields);
			},

			addVirtualhost() {
				this.loading = true;
				this.$http.post('web/vhost/add', {"ip": this.get_servers[this.$route.params.id].ip, "server_name": this.server_name, "server_ip": this.ipaddress} ).then((response) => {
					this.loading = false;
					this.$router.push({ name: 'web' });
					console.log(response);
				},
				(response) => {
					console.log("error addVirtualhost - ", response);
					this.loading = false;
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
