<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Ajouter un nouvel utilisateur | SFTP
			</div>
			<div class="column">
				<div class="row">
					<button class="ui blue disabled button">Profil</button>
				</div>
				<div class="ui divider"></div>


				<form v-on:submit.stop.prevent="" class="ui form column" id="adduser_sftp" style="padding-bottom: 50px" :class="{ 'loading': loading } ">
					<div class="ui stacked secondary" style="padding-bottom: 30px">
						<div class="required field">
							<label style="text-align: left">Nom de l'utilisateur</label>
							<div class="ui left icon input">
								<i class="user icon"></i>
								<input type="text" name="login" id="login" v-model="login" placeholder="Nom de l'utilisateur">
							</div>
						</div>
						<div class="required field">
							<label style="text-align: left">Password</label>
							<div class="ui left icon input">
								<i class="world icon"></i>
								<input type="password" id="password" name="password" v-model="password" placeholder="Password">
							</div>
						</div>

					</div>
					<div class="ui divider"></div>


					<div class="ui four column" style="padding-bottom: 30px">
						<div class="ui grid">
							<div class="ten wide column left aligned">
								<span class="fluid ui grey button" v-bind:class=" { 'grey': !options, 'orange': options }" @click="showOptions()">Droits des dossiers partagés</span>
							</div>
							<div class="four wide column"></div>
							<div class="two wide column right aligned">
								<i class="circular inverted link icon"
								v-bind:class=" { 'angle double down': !options, 'angle double up': options, 'grey': !options, 'orange': options } " @click="showOptions()"></i>
							</div>
						</div>
					</div>

					<transition name="bounce">
						<div class="ui grid" v-show="options" style="padding-top: 30px; overflow-y: auto">
							<div class="sixteen wide column" style="max-height: 200px">
								<div class="ui middle aligned divided list">
									<div class="item" v-for="(directory, index) in serverSFTP_Directories">
										<div class="right floated content" v-if="option_directories.length > 0">
											<input type="checkbox" v-model="option_directories[index].targeted">
										</div>
										<i class="folder icon"></i>
										<div class="content">
											<a class="header">{{directory.name}}</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</transition>

					<div class="ui four column stackable centered grid container">
						<div class="left floated column">
						<router-link class="ui red button" :to="{name: 'sftp'}">Cancel</router-link>
						</div>
						<div class="right aligned column">
							<button type="submit" class="ui green button">Ajouter</button>

						</div>
					</div>
					<div class="ui error message"></div>
				</form>
			</div>

		</div>
	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../../store'

	export default {

		name: 'SFTP_adduser',

		data () {
			return {
				options: false,
				loading: false,
				password: "",
				login: "",
				option_directories: [],
			};
		},

		mounted: function() {
			var _this = this;

			$('#adduser_sftp').form({
				fields: {
					login: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},
					password: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					}
				},

				onSuccess(event, field) {
					event.preventDefault();
					_this.adduser_sftp();
				}
			});
			for (var i = 0; i < this.serverSFTP_Directories.length; i++) {
				this.option_directories.push({targeted: false});
			}
		},

		methods: {
			...Vuex.mapActions(['add_SFTP_users', 'add_SFTP_usersInDirectory']),

			showOptions: function () {
				this.options = !this.options;
			},

			adduser_sftp: function() {

				this.loading = true;
				this.add_SFTP_users( {"server": this.get_servers[this.$route.params.id], "login": this.login, "passwd": this.password } ).then(response => {
					console.log(response);

					if (this.options) {
						this.loading = true;

						for (var i = 0; i < this.option_directories.length; i++) {
						this.loading = true;
							if (this.option_directories[i].targeted) {
								this.add_SFTP_usersInDirectory( {"server": this.get_servers[this.$route.params.id], "idx_directory": i, "login": this.login } ).then(response => {
									console.log(response);
									this.loading = false;
									this.$router.push({ name: 'sftp'});
								}, error => {
									console.log("error getSFTPconfig get SFTP users", error.error_msg);
									this.loading = false;

								})
							}
						}
					} else
						this.$router.push({ name: 'sftp'});
					this.loading = false;
				}, error => {
					console.log("error getSFTPconfig get SFTP users", error.error_msg);
					this.loading = false;

				})

			},
		},

		computed: {
			...Vuex.mapGetters(['get_servers']),

			serverSFTP_Users: function() {
				this.get_servers[this.$route.params.id].users;
				return this.get_servers[this.$route.params.id].users;
			},

			serverSFTP_Directories: function() {
				this.get_servers[this.$route.params.id].shared_directories;
				return this.get_servers[this.$route.params.id].shared_directories;
			}
		}
	};
</script>

<style lang="css" scoped>
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
