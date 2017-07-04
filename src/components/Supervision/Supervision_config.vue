<template>
	<div class="ui segment">

		<h4 class="ui horizontal divider header" style="padding-top: 30px; padding-bottom: 50px">
			<i class="settings icon"></i>
			Configuration général
		</h4>
		<form v-on:submit.prevent.stop="form_generalconfig()" class="ui form two column grid"
		id="general_config" style="padding-bottom: 50px">

		<div class="column">
			<div class="ui grid">
				<div class="ten wide centered column">
					<div class="ui stacked secondary" style="padding-bottom: 30px">
						<div class="required field">
							<label style="text-align: left">Hostname</label>
							<div class="ui left icon input">
								<i class="user icon"></i>
								<input type="text" name="gconfig_hostname" id="gconfig_hostname" v-model="gconfig_hostname" placeholder="Hostname">
							</div>
						</div>
						<div class="required field">
							<label style="text-align: left">IP d'écoute</label>
							<div class="ui left icon input">
								<i class="home icon"></i>
								<input type="text" name="gconfig_ip" id="gconfig_ip" v-model="gconfig_ip" placeholder="IP d'écoute">
							</div>
						</div>
						<div class="required field">
							<label style="text-align: left">Port d'écoute</label>
							<div class="ui left icon input">
								<i class="cube icon"></i>
								<input type="text" id="gconfig_port" name="gconfig_port" v-model="gconfig_port" placeholder="Port d'écoute">
							</div>
						</div>
						<div class="required field">
							<label style="text-align: left">Fichier PID</label>
							<div class="ui left icon input">
								<i class="cube icon"></i>
								<input type="text" id="gconfig_pid" name="gconfig_pid" v-model="gconfig_pid" placeholder="Fichier PID">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="column">
			<div class="ui grid">
				<div class="sixteen wide centered column">
					<div class="inline required fields" style="padding-top: 25px; padding-bottom: 30px">
						<label for="remote_command" style="padding-right: 50px;">Commande à distance :</label>
						<div class="field">
							<div class="ui radio checkbox">
								<input type="radio" name="remote_command" checked="" tabindex="0" class="hidden">
								<label>Activer</label>
							</div>
						</div>
						<div class="field">
							<div class="ui radio checkbox">
								<input type="radio" name="remote_command" tabindex="0" class="hidden">
								<label>Desactiver</label>
							</div>
						</div>
					</div>
					<div class="inline required fields" style="padding-bottom: 30px">
						<label for="log_cmd" style="padding-right: 50px">Log des commandes à distance :</label>
						<div class="field">
							<div class="ui radio checkbox">
								<input type="radio" name="log_cmd" checked=""  tabindex="0" class="hidden">
								<label>Activer</label>
							</div>
						</div>
						<div class="field">
							<div class="ui radio checkbox">
								<input type="radio" name="log_cmd" tabindex="0" class="hidden">
								<label>Desactiver</label>
							</div>
						</div>
					</div>
					<div class="inline required field" style="padding-bottom: 30px">
						<label>Niveau de debug :</label>
						<input type="range" v-model="range_value" min="0" max="4" step="1">
						<span>{{range_value}}</span>
					</div>
					<div class="ui stacked secondary" style="padding-bottom: 30px">
						<div class="required field">
							<label style="text-align: left">Hostname</label>
							<div class="ui left icon input">
								<i class="user icon"></i>
								<input type="text" name="gconfig_hostname" id="gconfig_hostname" v-model="gconfig_hostname" placeholder="Hostname">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="centered column">
			<div class="ui error message"></div>
		</div>
	</form>
	<h4 class="ui horizontal divider header" style="padding-top: 30px; padding-bottom: 50px">
		<i class="tasks icon"></i>
		Configuration de la base de données
	</h4>
	<form v-on:submit.stop.prevent="bdd_config" class="ui form two column grid"
	id="bdd_config" style="padding-bottom: 50px">

	<div class="column">
		<div class="ui grid">
			<div class="twelve wide centered column">
				<div class="ui stacked secondary" style="padding-bottom: 30px">
					<div class="required field">
						<label style="text-align: left">Type</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" name="bddconfig_type" id="bddconfig_type" v-model="bddconfig_type" placeholder="Type">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Adresse IP du serveur</label>
						<div class="ui left icon input">
							<i class="home icon"></i>
							<input type="text" name="bddconfig_ip" id="bddconfig_ip" v-model="bddconfig_ip" placeholder="Adresse IP du serveur">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Port</label>
						<div class="ui left icon input">
							<i class="cube icon"></i>
							<input type="text" id="bddconfig_port" name="bddconfig_port" v-model="bddconfig_port" placeholder="Port">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="column">
		<div class="ui grid">
			<div class="twelve wide centered column">
				<div class="ui stacked secondary" style="padding-bottom: 30px">
					<div class="required field">
						<label style="text-align: left">Nom de la base de donnée</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" name="bddconfig_name" id="bddconfig_name" v-model="bddconfig_name" placeholder="Nom de la base de donnée">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Utilisateur de la base donnée</label>
						<div class="ui left icon input">
							<i class="home icon"></i>
							<input type="text" name="bddconfig_user" id="bddconfig_user" v-model="bddconfig_user" placeholder="Utilisateur de la base donnée">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Mot de passe</label>
						<div class="ui left icon input">
							<i class="cube icon"></i>
							<input type="text" id="bddconfig_passwd" name="bddconfig_passwd" v-model="bddconfig_passwd" placeholder="Mot de passe">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="centered column">
		<div class="ui error message"></div>
	</div>
</form>
</div>
</template>

<script>
	export default {

		name: 'Supervision_config',

		data () {
			return {
				range_value: 1
			};
		},

		methods: {
			form_generalconfig() {
				console.log("ici");
			},

			bdd_config() {

			}
		},

		mounted: function() {
			$('.ui.radio.checkbox').checkbox();

			$('#general_config').form({
				fields: {
					gconfig_hostname: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},

					gconfig_ip: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},
					gconfig_port: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},
					gconfig_pid: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},
					remote_command: {
						rules: [
						{
							type : 'checked',
							prompt: 'Veuillez selectionner un des deux types de {name}.'
						}
						]
					},

					log_cmd: {
						rules: [
						{
							type : 'checked',
							prompt: 'Veuillez selectionner un des deux types de {name}.'
						}
						]
					}
				},

				onSuccess(event, field) {
					event.preventDefault();
				}
			});


			$('#bdd_config').form({
				fields: {
					bddconfig_type: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},

					bddconfig_ip: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},
					bddconfig_port: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},
					bddconfig_name: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},
					bddconfig_user: {
						rules: [
						{
							type : 'empty',
							prompt: 'Veuillez remplir le champ "{name}"'
						}
						]
					},

					bddconfig_passwd: {
						rules: [
						{
							type : 'empty',
							prompt: 'Veuillez remplir le champ "{name}"'
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

<style lang="scss" scoped>
</style>