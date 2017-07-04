<template>
<div class="ui segment">
	<div class="ui two column stackable centered grid container">
		<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
			Editer {{getSFTP_User}} | SFTP
		</div>
		<div class="column">
			<div class="row">
				<button class="ui blue disabled button">Profil</button>
			</div>
			<div class="ui divider"></div>

			<form v-on:submit.stop.prevent="" class="ui form column" :class=" { 'loading': loading.passwd } " id="edituserpasswd_sftp" style="padding-bottom: 50px">

				<div class="ui stacked secondary" style="padding-bottom: 30px">
					<div class="required field">
						<label style="text-align: left">Password</label>
						<div class="ui left icon input">
							<i class="world icon"></i>
							<input type="password" id="password" name="password" v-model="password" placeholder="Password">
						</div>
					</div>

				</div>
				<div class="ui four column stackable centered grid container">
					<div class="left floated column">
					</div>
					<div class="right aligned column">
						<button class="ui green button">Editer</button>
					</div>
				</div>
				<div class="ui error message"></div>


			</form>

			<form v-on:submit.stop.prevent="" class="ui form column" :class=" { 'loading': loading.directories } " id="edituserdirectories_sftp" style="padding-bottom: 20px">

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
										<input type="checkbox"v-model="option_directories[index].targeted">
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
				<div class="ui four column stackable centered grid container" v-show="options">
					<div class="left floated column">
					</div>
					<div class="right aligned column">
						<button class="ui green button" :class="{'disabled': !options}">Modifier</button>
					</div>
				</div>
				<div class="ui error message"></div>


			</form>
			<div class="ui four column stackable centered grid container">
				<div class="left floated column">
					<router-link class="ui red button" :to="{name: 'sftp'}">Cancel</router-link>
				</div>
				<div class="right aligned column">

				</div>
			</div>

		</div>
	</div>
</div>
</template>

<script>

import Vuex from 'vuex'
import store from '../../store'


export default {

	name: 'SFTP_edituser',

	data () {
		return {
			options: false,
			password: "",
			loading: {"passwd": false, "directories": false},
			option_directories: []
		};
	},

	mounted: function() {
		var _this = this;

		$('#edituserpasswd_sftp').form({
			fields: {
				password: {
					rules: [
					{
						type   : 'empty',
						prompt : 'Veuillez remplir le champ "{name}"'
					}
					]
				},
			},

			onSuccess(event, field) {
				event.preventDefault();
				_this.edituserpasswd_sftp();
			}
		});

		$('#edituserdirectories_sftp').form({
			fields: {
				
			},

			onSuccess(event, field) {
				event.preventDefault();
				_this.edituserdirectories_sftp();
			}
		});

		var idx_user = 0;

		for (var i = 0; i < this.serverSFTP_Directories.length; i++) {
			this.option_directories.push({targeted: false});
			while (idx_user < this.serverSFTP_Directories[i].associated_users.length && this.serverSFTP_Directories[i].associated_users[idx_user] != this.getSFTP_User)
				idx_user++;
			if (this.serverSFTP_Directories[i].associated_users[idx_user] == this.getSFTP_User)
				this.option_directories[i].targeted = true;
			else
				this.option_directories[i].targeted = false;
		}
	},

	methods: {

		...Vuex.mapActions(['add_SFTP_usersInDirectory', 'delete_SFTP_usersInDirectory']),

		showOptions: function () {
			this.options = !this.options;
		},

		edituserpasswd_sftp: function() {
			this.loading.passwd = true;
			this.$http.post('sftp/users/passwd', {"ip": this.get_servers[this.$route.params.id].ip, "login": this.getSFTP_User, "passwd": this.password } ).then((response) => {
				console.log(response);
				this.loading.passwd = false;
				this.$router.push({ name: 'sftp'});
			},
			(response) => {
				console.log("error edituserpasswd_sftp - ", response);
				this.loading.passwd = false;
			});
		},

		edituserdirectories_sftp: function() {

			var idx_user = 0;

			this.loading.directories =  true;


			for (var i = 0; i < this.option_directories.length; i++) {
				while (idx_user < this.serverSFTP_Directories[i].associated_users.length && this.serverSFTP_Directories[i].associated_users[idx_user] != this.getSFTP_User)
					idx_user++;
				if (this.option_directories[i].targeted && this.serverSFTP_Directories[i].associated_users[idx_user] != this.getSFTP_User) {
						//alors ici on ajoute le dossier à l'utilisateur

						this.add_SFTP_usersInDirectory( {"server": this.get_servers[this.$route.params.id], "idx_directory": i, "login": this.getSFTP_User } ).then(response => {
							console.log(response);
							this.loading.directories = false;
						}, error => {
							console.log("error add user in directory", error.error_msg);
							this.loading.directories = false;

						})
					}
					else if (!this.option_directories[i].targeted && this.serverSFTP_Directories[i].associated_users[idx_user] == this.getSFTP_User) {
						// alors ici on supprime le dossier à l'utilisateur

						this.delete_SFTP_usersInDirectory( {"server": this.get_servers[this.$route.params.id], "idx_directory": i, "idx_user": idx_user, "login": this.getSFTP_User } ).then(response => {
							console.log(response);
							this.loading.directories = false;
						}, error => {
							console.log("error delete user in directory ", error.error_msg);
							this.loading.directories = false;

						})
					}
					else
						this.loading.directories = false;
				}
			}
		},


		computed: {
			...Vuex.mapGetters(['get_servers']),

			serverName: function() {
				this.get_servers[this.$route.params.id].hostname;
				return this.get_servers[this.$route.params.id].hostname;
			},

			serverSFTP_Users: function() {
				this.get_servers[this.$route.params.id].users;
				return this.get_servers[this.$route.params.id].users;
			},

			serverSFTP_Directories: function() {
				this.get_servers[this.$route.params.id].shared_directories;
				return this.get_servers[this.$route.params.id].shared_directories;
			},

			getSFTP_User: function() {
				this.get_servers[this.$route.params.id].users;
				return this.get_servers[this.$route.params.id].users[this.$route.params.user].login;
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