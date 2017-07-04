<template>
	<div class="ui middle aligned center aligned grid">
		<div class="column">
			<h2 class="ui image header">
				<div class="content">
					Create a new account
				</div>
			</h2>
			<form v-on:submit.stop.prevent="signup" class="ui large form" id="signup">
				<div class="ui stacked secondary segment">
					<div class="field">
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" name="username" v-model="username" placeholder="Username">
						</div>
					</div>
					<div class="field">
						<div class="ui left icon input">
							<i class="lock icon"></i>
							<input type="password" name="password1" v-model="password1" placeholder="Password">
						</div>
					</div>
					<div class="field">
						<div class="ui left icon input">
							<i class="lock icon"></i>
							<input type="password" name="password2" v-model="password2" placeholder="Password">
						</div>
					</div>
					<button type="submit" class="ui fluid large teal button">Sign up</button>
				</div>

				<div class="ui error message"></div>
				<div class="ui warning message" v-show="error" style="display: block">This username is already used</div>

			</form>
			<div class="ui message">
				Click<router-link :to="{name: 'log in'}"> here </router-link> to log in
			</div>
		</div>
	</div>
</template>

<script>
	import Vuex from 'vuex'

	export default {

		name: 'Signup',

		data () {
			return {
				username: "",
				password1: "",
				password2: "",
				error: false
			};
		},

		methods: {
			...Vuex.mapActions(['set_LoggedIn']),

			signup () {
				this.error = false;
				if ($('.ui.form').form("is valid")) {
					this.$http.get('users', {params: { "username" : this.username} }).then((response) => {
						if (response.body.length == 0) {
							this.$http.post('users', { "username" : this.username, "password": this.password1 }).then((response) => {
								console.log("Creation de compte + redirection");
								this.set_LoggedIn(true);
								this.$router.push( { name :'home' } );
							},
							(response) => {
								console.log("error register a new account");
							});
						}
						else {
							this.error = true;
						}
					},
					(response) => {
						console.log("error signup function, can not access /users url");
					});
				}
			}
		},

		mounted: function() {
			this.error = false;
			$('#signup').form({
				fields: {
					username: {
						identifier  : 'username',
						rules: [
						{
							type   : 'empty',
							prompt : 'Please enter your username'
						}
						]
					},
					password1: {
						identifier  : 'password1',
						rules: [
						{
							type   : 'minLength[3]',
							prompt : 'Your password must be at least {ruleValue} characters'
						}
						]
					},
					password2: {
						identifier  : 'password2',
						rules: [
						{
							type   : 'match[password1]',
							prompt : 'Please enter the same password'
						}
						]
					}
				},

				onSuccess(event, field) {
					event.preventDefault();
				}
			});
		},

		beforeRouteEnter: function (to, from, next) {
			if (localStorage.token === "true") {
				next({ path: '/' });
			} else {
				next();
			}
		}


	};
</script>

<style lang="css" scoped>
	.image {
		margin-top: -100px;
	}
	.column {
		max-width: 450px;
	}
</style>