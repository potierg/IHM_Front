<template>
	<div class="ui segment">
		<div class="ui left aligned grid row">

			<div v-bind:class="{ 'active': requestState.users == false, 'active': requestState.directories == false }" class="ui dimmer">
				<div class="ui indeterminate text loader">Fetching data</div>
			</div>

			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				{{serverName}} | SFTP
			</div>


			<div class="fourteen wide column" style="padding-bottom: 20px; border-radius: 0px; border-bottom: 1px solid #ccc">
				<div class="ui top attached tabular menu">
					<a class="item active" data-tab="users">Utilisateurs</a>
					<a class="item" data-tab="shared_directory">Dossiers partagés</a>
				</div>
				<div class="ui bottom attached tab segment active" data-tab="users">
					<div class="ui middle aligned divided list" v-if="targeted_users.length > 0">
						<div class="item" v-for="(user, index) in serverSFTP_Users" v-bind:class=" { 'target_color': targeted_users[index].targeted }" @click="target_user(index)">
							<i class="ui avatar user icon"></i>
							<div class="content">
								<a class="header">{{user.login}}</a>
							</div>
						</div>
					</div>
				</div>
				<div class="ui bottom attached tab segment" data-tab="shared_directory">
					<div class="ui middle aligned divided list" v-if="targeted_directory.length > 0">
						<div class="item" v-for="(directory, index) in serverSFTP_Directories" v-bind:class=" { 'target_color': targeted_directory[index].targeted == true }" @click="target_directory(index)">
							<i class="folder icon" ></i>
							<div class="content">
								<a class="header">{{directory.name}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="two wide column" style="border-radius: 0px; border-left: 1px solid #ccc; padding-bottom: 30px">
				<div style="margin-top: 30%;">
					<div class="row">
						<button class="ui green button large" @click="addSomething()" style="width: 100%; font-size: 0.8em">Ajouter</button>
					</div>
					<div class="row">
						<button class="ui orange button large" @click="editSomething()" style="width: 100%; font-size: 0.8em">Editer</button>
					</div>
					<div class="row">
						<button class="ui red button" style="width: 100%; font-size: 0.8em" @click="deleteSomething()">Supprimer</button>
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

		name: 'SFTP',

		data () {
			return {
				requestState: {"users": false, "directories": false},
				id_target_user: -1,
				id_target_shared_directory: -1,
				/* 0 = utilisateurs / 1 = dossiers partagés */
				current_tab: 0,
				targeted_users: [],
				targeted_directory: [],

			};
		},

		mounted: function() {
			var _this = this;

			$('.menu .item').tab();
			$('.menu .item').tab({'onVisible':function(tabPath){ tabPath == 'users' ? _this.current_tab = 0 : _this.current_tab = 1;}});

			this.getSFTPconfig();
		},

		methods: {
			...Vuex.mapActions(['get_SFTP_users', 'get_SFTP_sharedDirectories', 'get_SFTP_usersInsharedDirectories']),
			
			target_user: function(index) {
				if (this.id_target_user == -1) {
					this.targeted_users[index].targeted = true;
					this.id_target_user = index;
				} else {
					this.targeted_users[this.id_target_user].targeted = false;
					this.targeted_users[index].targeted = true;
					this.id_target_user = index;
				};
			},

			target_directory: function(index) {
				if (this.id_target_shared_directory == -1) {
					this.targeted_directory[index].targeted = true;
					this.id_target_shared_directory = index;
				} else {
					this.targeted_directory[this.id_target_shared_directory].targeted = false;
					this.targeted_directory[index].targeted = true;
					this.id_target_shared_directory = index;
				}
			},

			addSomething: function() {
				if (this.current_tab == 0) {
					this.$router.push({ name: 'sftp.adduser' });
				} else if (this.current_tab == 1) {
					this.$router.push({ name: 'sftp.adddirectory' });

				}
			},

			editSomething: function() {
				if (this.current_tab == 0 && this.id_target_user != -1) {
					this.$router.push({ name: 'sftp.edituser', params: { "user": this.id_target_user } });
				} else if (this.current_tab == 1 && this.id_target_shared_directory != -1) {
					this.$router.push({name: 'sftp.editdirectory'});
				}
			},

			deleteSomething: function() {
				if (this.current_tab == 0 && this.id_target_user != -1) {
					this.delete_user();
				} else if (this.current_tab == 1 && this.id_target_shared_directory != -1) {
					this.delete_shared_directory();
				}
			},

			delete_user: function() {
				console.log("delete_user", {"ip": this.get_servers[this.$route.params.id].ip, "login": this.serverSFTP_Users[this.id_target_user].login});
				this.$http.post('sftp/users/delete', {"ip": this.get_servers[this.$route.params.id].ip, "login": this.serverSFTP_Users[this.id_target_user].login } ).then((response) => {
					console.log(response);

					//store.commit('DELETE_SFTP_USER', {"server": this.get_servers[this.$route.params.id], "id_user": this.id_target_user});

				},
				(response) => {
					console.log(response);
				});
			},

			delete_shared_directory: function() {
				this.$http.post('sftp/shared_folders/delete', {"ip": this.get_servers[this.$route.params.id].ip, "name_folder": this.get_servers[this.$route.params.id].shared_directories[this.id_target_shared_directory]} ).then((response) => {

					store.commit('DELETE_SFTP_DIRECTORY', {"server": this.get_servers[this.$route.params.id], "id_directory": this.id_target_shared_directory});

				},
				(response) => {
					console.log(response);
				});
			},

			getSFTPconfig: function() {

				if (!this.get_servers[this.$route.params.id].hasOwnProperty("users")) {

					this.get_SFTP_users( this.get_servers[this.$route.params.id] ).then(response => {
						for (var i = 0; i < response.body.response.length; i++) {
							this.targeted_users.push( {"targeted" : false});
						}
						this.requestState.users = true;

					}, error => {
						console.log("error getSFTPconfig get SFTP users", error);
						this.requestState.users = true;

					})
				}

				if (!this.get_servers[this.$route.params.id].hasOwnProperty("shared_directories")) {

					this.get_SFTP_sharedDirectories( this.get_servers[this.$route.params.id] ).then(response => {
						for (var i = 0; i < response.body.response.shared_folders.length; i++) {
							this.targeted_directory.push( {"targeted" : false});
						};
						this.getSFTPusersIndirectories();


					}, error => {
						console.log("error getSFTPconfig get SFTP directories", error);
						this.requestState.directories = true;
					})

				}

				if (this.get_servers[this.$route.params.id].hasOwnProperty("users") && this.get_servers[this.$route.params.id].hasOwnProperty("shared_directories")) {
					for (var i = 0; i < this.serverSFTP_Users.length; i++) {
						this.targeted_users.push( {"targeted" : false});
					}
					for (var i = 0; i < this.serverSFTP_Directories.length; i++) {
						this.targeted_directory.push( {"targeted" : false});
					}
					this.requestState.directories = true;
					this.requestState.users = true;
				}
			},

			getSFTPusersIndirectories: function() {
				for (var i = 0; i < this.get_SFTP_sharedDirectories.length; i++) {
					this.get_SFTP_usersInsharedDirectories( { "server": this.get_servers[this.$route.params.id], "directory": this.serverSFTP_Directories[i].name, "idx_directory": i } ).then(response => {
						console.log(response);
						if (i + 1 >= this.get_SFTP_usersInsharedDirectories.length)
							this.requestState.directories = true;

					}, error => {
						console.log("error getSFTPusersIndirectories", error);
							this.requestState.directories = true;

					})
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
		}

	};
</script>

<style lang="css" scoped>
	.target_color {
		background-color: rgba(192,192,192, 0.5);
	}
</style>