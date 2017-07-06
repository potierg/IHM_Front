<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Nouveau mirror local
			</div>

			<form v-on:submit.stop.prevent="" class="ui form column" v-bind:class="{ 'loading': loading }" id="addnewrepoupdate" style="padding-bottom: 50px">

				<div class="ui stacked secondary">
					<div class="required field">
						<label style="text-align: left">Url du mirror</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
              <input type="text" name="repo_url" id="repo_url" v-model="repo_url" placeholder="url du mirror">
						</div>
					</div>
				</div>

				<div class="ui grid" style="padding-top: 30px;">
					<div class="six column row">
						<div class="column"></div>
						<button class="ui green button" @click="">Save changes</button>
						<div class="column"></div>
						<div class="column"></div>
						<router-link class="ui red button" :to="{name: 'maj'}">Cancel</router-link>
						<div class="column"></div>
					</div>
				</div>
				<div class="ui error message"></div>
			</form>

		</div>
	</div>
</template>


<script>

	import Vuex from 'vuex'
	import store from '../../store'

	export default {

		name: 'MAJ_addmirror',

		data () {
			return {
				loading: false,
				'repo_url_form': {
          rules: [{type   : 'empty', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}'}]
				},
				repo_url: "",
			};
		},

		methods: {

			initForm() {
				$('#addnewrepoupdate').form('destroy');
				let _this = this;

				var fields = {
					fields: {
						repo_url: this.repo_url_form
					},

					onSuccess(event, field) {
						event.preventDefault();
						_this.addMirror();
					}

				};
				$('#addnewrepoupdate').form(fields);
			},

			addMirror() {
				this.loading = true;
        let req = {
          "ip":  this.get_servers[this.$route.params.id].ip,
          "url": this.repo_url
        };
        console.log(req);
				this.$http.post('update/package/add', req ).then((response) => {
					this.loading = false;
					this.$router.push({ name: 'maj' });
					console.log(response);
				},
				(response) => {
					console.log("error addMirrorUpdate - ", response);
					this.loading = false;
				});
			}

		},

		computed: {
			...Vuex.mapGetters(['get_servers']),

		},

		mounted: function() {
			this.initForm();

		}
	};
</script>

<style lang="css" scoped>

	.custom_form {
		padding-bottom: 0px;
		padding-top: 0px;
		margin: 0 0 .28571429rem;
		font-size: .92857143em;
		font-weight: 700
	}
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
