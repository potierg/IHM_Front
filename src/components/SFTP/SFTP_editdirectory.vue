<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Editer le dossier partagé 'Eloryal' | SFTP
			</div>
			<div class="column">

				<form v-on:submit.stop.prevent="editdirectory_sftp" class="ui form column" id="editdirectory_sftp" style="padding-bottom: 50px">

					<div class="ui middle aligned divided list" style="padding-bottom: 20px; padding-right: 10px; padding-left: 10px; padding-top: 20px; margin-top: 30px
					border-radius: 15px; border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc; max-height: 200px; overflow-y: auto">
					<div class="item" v-for="(user, index) in users">
						<div class="right floated content">
							<input type="checkbox" v-model="user.targeted">
						</div>
						<i class="user icon"></i>
						<div class="content">
							<a class="header">{{user.name}}</a>
						</div>
					</div>
				</div>
				<div class="ui four column stackable centered grid container" style="padding-top: 30px">
					<div class="left floated column">
					</div>
					<div class="right aligned column">
						<button class="ui green button">Enregistrer</button>
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
	export default {

		name: 'SFTP_editdirectory',

		data () {
			return {
				users: [{name: "Daniel Louise", targeted: false}, {name: "Stevie Feliciano", targeted: false }, {name: "Elliot Fu", targeted: false},{name: "Daniel Louise", targeted: false}, {name: "Stevie Feliciano", targeted: false }, {name: "Elliot Fu", targeted: false},{name: "Daniel Louise", targeted: false}, {name: "Stevie Feliciano", targeted: false }, {name: "Elliot Fu", targeted: false},],
			};
		},

		mounted: function() {


			$('#editdirectory_sftp').form({
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
				}
			});
		}
	};
</script>

<style lang="css" scoped>
</style>