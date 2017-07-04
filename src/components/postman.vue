<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Postman like
			</div>
			<form v-on:submit.stop.prevent="" class="ui form column" v-bind:class="{ 'loading': loading == true}" id="postman" style="padding-bottom: 50px">
				<div class="ui stacked secondary" style="padding-bottom: 30px">
					<div class="required field">
						<label style="text-align: left">Adresse ip</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" name="adresse_ip" id="adresse_ip" v-model="adresse_ip" placeholder="Uniquement l'extention ( addserver, is_on ... ) PAS DE / AU DEBUT">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Methode http</label>
						<select class="ui fluid search dropdown" tabindex="0" name="http_method" id="http_method" v-model="http_method">
							<option value="get">get</option>
							<option value="post">post</option>
							<option value="put">put</option>
							<option value="delete">delete</option>

						</select>
					</div>
					<div class="field">
						<label style="text-align: left">Objet à ajouter en paramètre (json) ( utiliser "" pour des strings )</label>
						<textarea v-model="object_json" placeholder='{ "ip": "127.0.0.1"}'></textarea>
					</div>

				</div>
				<div class="ui divider"></div>

				<div class="ui four column stackable centered grid container">
					<div class="left floated column">
					</div>
					<div class="right aligned column">
						<button class="ui green button">Ajouter</button>

					</div>
				</div>
				<div class="field">
					<label style="text-align: left">Retours de la requête</label>
					<textarea v-model="msg" rows="20" placeholder="Retours de la requête"></textarea>
				</div>
				<div class="ui error message"></div>
			</form>
		</div>
	</div>
</template>

<script>
	export default {

		name: 'postman',

		data () {
			return {
				adresse_ip: "",
				http_method: "",
				object_json: "",
				msg: "",
				fct_pointer: {'get': this.formGet, 'post': this.formPost, 'put': this.formPut, 'delete': this.formDelete },
				loading: false
			};
		},


		methods: {
			formPostman: function() {
				this.loading = true;
				this.fct_pointer[this.http_method]();
			},

			formGet() {
				this.$http.get(this.adresse_ip).then((response) => {
					this.showState(response);
				},
				(response) => {
					console.log("error formGet function");
					this.showState(response);
				});
			},

			formPost() {
				this.$http.post(this.adresse_ip, JSON.parse(this.object_json)).then((response) => {
					this.showState(response);
				},
				(response) => {
					console.log("error formPost function");
					this.showState(response);
				});
			},

			formPut() {
				this.$http.put(this.adresse_ip, JSON.parse(this.object_json)).then((response) => {
					this.showState(response);
				},
				(response) => {
					console.log("error formPut function");
					this.showState(response);
				});
			},

			formDelete() {
				this.$http.delete(this.adresse_ip).then((response) => {
					this.showState(response);
				},
				(response) => {
					console.log("error formDelete function");
					this.showState(response);
				});
			},

			showState: function(response) {
				console.log(response);
				this.msg = response;
				this.loading = false;
			}
		},

		mounted: function() {

			var _this = this;

			$('#http_method').dropdown();
			$('#http_method').dropdown('set value', "get");

			$('#postman').form({
				fields: {
					adresse_ip: {
						rules: [
						{
							type   : 'empty',
							prompt : '{name} must have a value'
						}
						]
					},
					http_method: {
						rules: [
						{
							type   : 'empty', // A APPROFONDIR
							prompt : 'Please select a correct {name}'
						}
						]
					}
				},

				onSuccess(event, field) {
					event.preventDefault();
					_this.formPostman();
				}
			})
		}
	};
</script>

<style lang="css" scoped>
</style>