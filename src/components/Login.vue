<template>
	<div class="ui middle aligned center aligned grid">
		<div class="column">
			<h2 class="ui image header">
				<div class="content">
					Log-in to your account
				</div>
			</h2>
			<form v-on:submit.stop.prevent="login" class="ui large form" id="login">
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
							<input type="password" name="password" v-model="password" placeholder="Password">
						</div>
					</div>
					<button type="submit" class="ui fluid large teal button">Login</button>
				</div>
				<div class="ui error message"></div>
				<div class="ui warning message" v-show="error" style="display: block">Incorrect username or password.</div>

			</form>
			{{username}}
			<div class="ui message">
				New to eloryal? <router-link :to="{name: 'sign up'}">Register</router-link>
			</div>
		</div>
	</div>
</template>

<script>
	import Vuex from 'vuex'

	export default {

		name: 'Login',

		data () {
			return {
				username: 'joe@example.com',
				password: 'password1',
				error: false,
			};
		},

		methods: {
			...Vuex.mapActions(['set_LoggedIn']),

			login () {
				if ($('.ui.form').form("is valid")) {

					this.$http.get('users', {params: { "username" : this.username, "password": this.password } }).then((response) => {

						if (response.body.length > 0) {
							this.set_LoggedIn(true);
							this.$router.push( { name :'home' } );
						} else {
							this.error = true;
							console.log("nothing about" + this.username + " " + this.password, response);
						}

					},
					(response) => {
						console.log("error login function, can not access /users url");
					});

			}
		},

		logout: function() {
			this.set_LoggedIn(false);
		}
	},

	mounted: function() {
		this.error = false;
		console.log("mounted est appeller à chaque fois que la vue est réafficher à l'écran");
		$('#login').form({
			fields: {
				username: {
					rules: [
					{
						type   : 'empty',
						prompt : 'Please enter your username'
					}
					]
				},
				password: {
					rules: [
					{
						type   : 'minLength[3]',
						prompt : 'Your password must be at least {ruleValue} characters'
					}
					]
				}
			},

			onSuccess(event, field) {
				event.preventDefault();
			}
		})
	},

	beforeRouteEnter: function (to, from, next) {
		if (localStorage.token === "true") {
			next({ path: '/' });
		} else {
			next();
		}
	}
}
</script>

<style lang="css" scoped>
	.image {
		margin-top: -100px;
	}
	.column {
		max-width: 450px;
	}
</style>