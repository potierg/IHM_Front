<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Ajouter un nouveau dossier de partage | SFTP
			</div>
			<div class="column">

				<form v-on:submit.stop.prevent="" class="ui form column" :class="{ 'loading': loading }" id="adddirectory_sftp" style="padding-bottom: 50px">
					<div class="ui stacked secondary" style="padding-bottom: 30px">
						<div class="required field">
							<label style="text-align: left">Nom du dossier de partage</label>
							<div class="ui left icon input">
								<i class="folder icon"></i>
								<input type="text" name="name_directory" id="name_directory" v-model="name_directory" placeholder="Nom du dossier de partage">
							</div>
						</div>
					</div>
					<div class="ui divider"></div>

					<h5>Liste des utilisateurs</h5>

					<div class="ui middle aligned divided list" style="padding-bottom: 20px; padding-right: 10px; padding-left: 10px; padding-top: 20px; margin-top: 30px
					border-radius: 15px; border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc; max-height: 200px; overflow-y: auto">
					<div class="item" v-for="(user, index) in serverSFTP_Users">
						<div class="right floated content" v-if="option_users.length > 0">
							<input type="checkbox" v-model="option_users[index].targeted">
						</div>
						<i class="user icon"></i>
						<div class="content">
							<a class="header">{{user.login}}</a>
						</div>
					</div>
				</div>
				<div class="ui four column stackable centered grid container" style="padding-top: 30px">
					<div class="left floated column">
						<router-link class="ui red button" :to="{name: 'sftp'}">Cancel</router-link>
					</div>
					<div class="right aligned column">
						<button class="ui green button">Ajouter</button>
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

		name: 'SFTP_adddirectory',

		data () {
			return {
				name_directory: "",
				option_users: [],
				loading: false
			};
		},

		mounted: function() {

			var _this = this;

			$('#adddirectory_sftp').form({
				fields: {
					name_directory: {
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
					_this.adddirectory_sftp();
				}
			});

			for (var i = 0; i < this.serverSFTP_Users.length; i++) {
				this.option_users.push({targeted: false});
			}
		},

		methods: {
			...Vuex.mapActions(['add_SFTP_sharedDirectory']),

			adddirectory_sftp: function() {
				this.loading = true;
				this.add_SFTP_sharedDirectory( { "server": this.get_servers[this.$route.params.id], "directory": this. name_directory } ).then(response => {
					console.log(response);
					this.loading = false;
				}, error => {
					console.log(error);
					this.loading = false;
				})
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
</style>