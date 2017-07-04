<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Ajouter une machine
			</div>
			<form v-on:submit.stop.prevent="add_machine" class="ui form column" id="add_machine" style="padding-bottom: 50px">
				<div class="ui grid container">
					<div class="row centered aligned">
						<div class="inline required fields">
							<label for="verification" style="padding-right: 50px">Vérification :</label>
							<div class="field">
								<div class="ui radio checkbox">
									<input type="radio" name="verification" checked="" tabindex="0" class="hidden">
									<label>Passive</label>
								</div>
							</div>
							<div class="field">
								<div class="ui radio checkbox">
									<input type="radio" name="verification" tabindex="0" class="hidden">
									<label>Active</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row centered aligned">
						<div class="inline required fields">
							<label for="type" style="padding-right: 50px; ">Type :</label>
							<div class="field">
								<div class="ui radio checkbox">
									<input type="radio" name="type" checked="" tabindex="0" class="hidden">
									<label>IP</label>
								</div>
							</div>
							<div class="field">
								<div class="ui radio checkbox">
									<input type="radio" name="type" tabindex="0" class="hidden">
									<label>Nom d'hôte</label>
								</div>
							</div>
						</div>
					</div>
					<div class="sixteen wide column">
						<div class="ui stacked secondary" style="">
							<div class="required field">
								<label style="text-align: left">Machine</label>
								<div class="ui left icon input">
									<i class="folder icon"></i>
									<input type="text" name="name_machine" id="name_machine" v-model="name_machine" placeholder="Nom de la machine">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ui four column stackable centered grid container">
						<div class="left floated column">
						</div>
						<div class="right aligned column">
							<button class="ui green button">Ajouter</button>

						</div>
					</div>
				<div class="ui error message"></div>
			</form>
		</div>
	</div>
</template>

<script>
	export default {

		name: 'Supervision_addserver',

		data () {
			return {
				verification: true
			};
		},

		mounted: function() {
			$('.ui.radio.checkbox').checkbox();

			

			$('#add_machine').form({
				fields: {
					name_machine: {
						rules: [
						{
							type   : 'empty',
							prompt : 'Veuillez remplir le champ "{name}"'
						}
						]
					},

					verification: {
						rules: [
						{
							type : 'checked',
							prompt: 'Veuillez selectionner une des deux vérifications.'
						}
						]
					},
					type: {
						rules: [
						{
							type : 'checked',
							prompt: 'Veuillez selectionner un des deux types.'
						}
						]
					}
				},

				onSuccess(event, field) {
					event.preventDefault();
				}
			});
		},

		methods: {
			add_machine: function() {

			}
		}
	};
</script>

<style lang="css" scoped>
</style>