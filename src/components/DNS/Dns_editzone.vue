<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Edition de la zone DNS "{{this.$route.params.zone.name}}"
			</div>

			<form v-on:submit.stop.prevent="" class="ui form column" v-bind:class="{ 'loading': loading }" id="addnewzonedns" style="padding-bottom: 50px">

				<div class="ui stacked secondary">
					<div class="required field">
						<label style="text-align: left">Nom de la zone</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="zonename" id="zonename" v-model="zonename" placeholder="ex: eloryal.fr">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Type</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="zonetype" id="zonetype" v-model="zonetype" placeholder="ex: master">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Ficher</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" id="zonefile" name="zonefile" v-model="zonefile" placeholder="ex: db.eloryal.fr">
						</div>
					</div>
          <div class="field">
						<label style="text-align: left">Notification</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" id="zonenotif" name="zonenotif" v-model="zonenotif" placeholder="no or yes">
						</div>
					</div>
				</div>

				<div class="ui divider" style="margin-top: 40px"></div>
				<div class="ui three column stackable centered grid container">
					<div class="left floated column">
						<span class="ui grey button" v-bind:class=" { 'grey': !allow_query, 'violet': allow_query } " @click="showallow_query()">Allow Query</span>
					</div>
					<div class="right aligned column">
						<i class="circular inverted teal link icon"
						v-bind:class=" { 'angle double down': !allow_query, 'angle double up': allow_query, 'grey': !allow_query, 'violet': allow_query } " @click="showallow_query()"></i>
					</div>
				</div>

				<transition name="bounce">
					<div class="ui grid" v-show="allow_query" style="padding-top: 30px;">

						<div class="sixteen wide column row">
							<div class="field fourteen wide column">
								<label style="text-align: left">Adress IP n° 1</label>
								<div class="ui left icon input">
									<i class="server icon"></i>
									<input type="text" name="zoneallowquery" id="zoneallowquery" v-model="zoneallowquery" placeholder="ex: 192.168.13.58">
								</div>
							</div>
							<div class="two wide column">
								<i class="add big square green link icon" @click="add_new_allow_query()" style="font-size: 2.8em; padding-right: 33.59px; margin-top: 20px"></i>
							</div>
						</div>

						<transition-group class="sixteen wide column " name="bounce" tag="div">
							<div class="ui grid" v-for="(domain_name, index) in bonus_zone_allow_query" v-bind:key="index">
								<div class="field fourteen wide column">
									<label style="text-align: left">Adress IP n° {{ index + 2  }}</label>
									<div class="ui left icon input">
										<i class="server icon"></i>
										<input type="text" v-bind:id="'allow_query_' + index" v-model="bonus_zone_allow_query[index]" placeholder="ex: 192.168.13.58">
									</div>
								</div>
								<div class="two wide column">
									<i class="minus big square red link icon" @click="removeOneAllowQuery(index)" style="font-size: 2.8em; padding-right: 33.59px; margin-top: 20px"></i>
								</div>
							</div>
						</transition-group>

					</div>
				</transition>

				<div class="ui divider" style="margin-top: 40px"></div>
        <div class="ui three column stackable centered grid container">
					<div class="left floated column">
						<span class="ui grey button" v-bind:class=" { 'grey': !forwarders, 'violet': forwarders } " @click="showforwarders()">Forwarders</span>
					</div>
					<div class="right aligned column">
						<i class="circular inverted teal link icon"
						v-bind:class=" { 'angle double down': !forwarders, 'angle double up': forwarders, 'grey': !forwarders, 'violet': forwarders } " @click="showforwarders()"></i>
					</div>
				</div>

				<transition name="bounce">
					<div class="ui grid" v-show="forwarders" style="padding-top: 30px;">

						<div class="sixteen wide column row">
							<div class="field fourteen wide column">
								<label style="text-align: left">Adress IP n° 1</label>
								<div class="ui left icon input">
									<i class="server icon"></i>
									<input type="text" name="zoneforwarders" id="zoneforwarders" v-model="zoneforwarders" placeholder="ex: 192.168.13.58">
								</div>
							</div>
							<div class="two wide column">
								<i class="add big square green link icon" @click="add_new_forwarders()" style="font-size: 2.8em; padding-right: 33.59px; margin-top: 20px"></i>
							</div>
						</div>

						<transition-group class="sixteen wide column " name="bounce" tag="div">
							<div class="ui grid" v-for="(domain_name, index) in bonus_zone_forwarders" v-bind:key="index">
								<div class="field fourteen wide column">
									<label style="text-align: left">Adress IP n° {{ index + 2  }}</label>
									<div class="ui left icon input">
										<i class="server icon"></i>
										<input type="text" v-bind:id="'forwarders_' + index" v-model="bonus_zone_forwarders[index]" placeholder="ex: 192.168.13.58">
									</div>
								</div>
								<div class="two wide column">
									<i class="minus big square red link icon" @click="removeOneforwarders(index)" style="font-size: 2.8em; padding-right: 33.59px; margin-top: 20px"></i>
								</div>
							</div>
						</transition-group>

					</div>
				</transition>

				<div class="ui divider" style="margin-top: 40px"></div>

				<div class="ui grid" style="padding-top: 30px;">
					<div class="six column row">
						<div class="column"></div>
						<button class="ui green button" @click="">Save changes</button>
						<div class="column"></div>
						<div class="column"></div>
						<router-link class="ui red button" :to="{name: 'dns'}">Cancel</router-link>
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

		name: 'Dns_editZone',

		data () {
			return {
				loading: false,
				allow_query: false,
        forwarders: false,
				client_hote: false,
				'zonename_form': {
					rules: [{type: 'empty', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}' }]
				},
				'zonetype_form': {
					rules: [{type: 'empty', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}'}]
				},
				'zonefile_form': {
					rules: [{type: 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},
        'zonenotif_form': {
          rules: [{type: 'empty', // A APPROFONDIR
          prompt : 'Veuillez remplir correctement le champs {name}'}]
        },

				zonename: "",
				zonetype: "",
				zonefile: "",
        zonenotif: "",
				zoneallowquery: "",
				bonus_zone_allow_query: [],
        zoneforwarders: "",
        bonus_zone_forwarders: [],
			};
		},

		methods: {
			...Vuex.mapActions(['add_Subnet']),

			initForm() {
				$('#addnewzonedns').form('destroy');
				let _this = this;

				var fields = {
					fields: {
						zonename: this.zonename_form,
						zonetype: this.zonetype_form,
						zonefile: this.zonefile_form
					},

					onSuccess(event, field) {
						event.preventDefault();
						_this.editZone();
					}

				};

				$('#addnewzonedns').form(fields);
			},

			showallow_query() {
				this.allow_query = !this.allow_query;
				this.$nextTick(function () {
					this.initForm();
				})
			},

      showforwarders() {
        this.forwarders = !this.forwarders;
        this.$nextTick(function () {
          this.initForm();
        })
      },

			showClienthote() {
				this.client_hote = !this.client_hote;
				this.$nextTick(function () {
					this.initForm();
				})
			},

			add_new_allow_query() {
				this.bonus_zone_allow_query.push("");
				this.$nextTick(function () {
					this.initForm();
				})

				console.log($('addnewzonedns'));
			},

			removeOneAllowQuery(index) {
				this.bonus_zone_allow_query.splice(index, 1)
				this.$nextTick(function () {
					this.initForm();
				})
			},

      add_new_forwarders() {
        this.bonus_zone_forwarders.push("");
        this.$nextTick(function () {
          this.initForm();
        })

        console.log($('addnewzonedns'));
      },

      removeOneforwarders(index) {
        this.bonus_zone_forwarders.splice(index, 1)
        this.$nextTick(function () {
          this.initForm();
        })
      },

			getZone() {
				var _this = this;

				this.zonename = this.$route.params.zone.name;
				this.zonetype = this.$route.params.zone.type;
				this.zonefile = this.$route.params.zone.file;
				this.zonenotif = this.$route.params.zone.notify;

				if (this.$route.params.zone["allow-query"]) {
					if (this.$route.params.zone["allow-query"].length > 0) {
						this.zoneallowquery = this.$route.params.zone["allow-query"][0];
						this.allow_query = true;
					}
					this.$route.params.zone["allow-query"].forEach(function(element, index) {
						if (index > 0)
							_this.bonus_zone_allow_query.push(element);
					});
				}
				if (this.$route.params.zone["forwarders"]) {
					if (this.$route.params.zone["forwarders"].length > 0) {
						this.zoneforwarders = this.$route.params.zone["forwarders"][0];
						this.forwarders = true;
					}
					this.$route.params.zone["forwarders"].forEach(function(element, index) {
						if (index > 0)
							_this.bonus_zone_forwarders.push(element);
					});
				}
			},

			editZone() {
				this.loading = true;
				let allow_query_array = [];
        let forwarders_array = [];
        let newconf = {
          "name": this.zonename,
          "type": this.zonetype,
          "file": this.zonefile,
          "notify": this.zonenotif
        };
				if (this.allow_query) {
					allow_query_array.push(this.zoneallowquery);
					this.bonus_zone_allow_query.forEach((domain) => {
						allow_query_array.push(domain);
					});
					newconf["allow-query"] = allow_query_array;
				}
        if (this.forwarders) {
          forwarders_array.push(this.zoneforwarders);
          this.bonus_zone_forwarders.forEach((domain) => {
            forwarders_array.push(domain);
          });
          newconf.forwarders = forwarders_array;
        }
				this.$http.post('dns/zone/edit', {"ip": this.get_servers[this.$route.params.id].ip, "zone_name": this.zonename, "conf": newconf }).then((response) => {
					this.loading = false;
					this.$router.push({ name: 'dns' });
					console.log(response);
				},
				(response) => {
					console.log("error editZoneDns - ", response);
					this.loading = false;
				});
			}

		},

		computed: {
			...Vuex.mapGetters(['get_servers'])
		},

		mounted: function() {
			this.initForm();
			this.getZone();
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
