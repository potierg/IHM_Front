<template>
	<div class="ui segment">
		<div class="ui two column stackable centered grid container">
			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Edition du virtualhost "{{this.$route.params.host.server_name}}"
			</div>

			<form v-on:submit.stop.prevent="" class="ui form column" v-bind:class="{ 'loading': loading }" id="aditvirtualhostweb" style="padding-bottom: 50px">
        <!--CONF GENERAL-->
				<div class="ui stacked secondary">
					<div class="required field">
						<label style="text-align: left">Adresse IP</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="ipaddresszone" id="ipaddress" v-model="ipaddress" placeholder="IP address">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Serveur admin</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="serverAdmin" id="serverAdmin" v-model="serverAdmin" placeholder="serverAdmin">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Nom de serveur</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" id="serverName" name="serverName" v-model="serverName" placeholder="serverName">
						</div>
					</div>
          <div class="required field">
            <label style="text-align: left">Alias du serveur</label>
            <div class="ui left icon input">
              <i class="user icon"></i>
              <input type="text" id="serverAlias" name="serverAlias" v-model="serverAlias" placeholder="serverAlias">
            </div>
          </div>
          <div class="required field">
						<label style="text-align: left">Document root</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" id="docRoot" name="docRoot" v-model="docRoot" placeholder="Document root">
						</div>
					</div>
          <div class="field">
						<label style="text-align: left">Alias</label>
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" id="alias" name="alias" v-model="alias" placeholder="Alias">
						</div>
					</div>
				</div>

        <!--DIRECTORIES-->
				<div class="ui divider" style="margin-top: 40px"></div>

				<div class="ui four column stackable centered grid container">
					<div class="left floated column">
						<span class="ui grey button" v-bind:class=" { 'grey': !directories, 'violet': directories } " @click="showDirectory()">Directories</span>
					</div>
					<div class="right aligned column">
						<i class="circular inverted teal link icon"
						v-bind:class=" { 'angle double down': !directories, 'angle double up': directories, 'grey': !directories, 'violet': directories } " @click="showDirectory()"></i>
					</div>
				</div>

				<transition name="bounce">
					<div class="ui grid" v-show="directories" style="padding-top: 30px;">

						<div class="sixteen wide column row">
							<div class="field fourteen wide column">
								<label style="text-align: left; font-size: 1.2em;">Directory</label>
							</div>
              <div class="two wide column">
                <i class="add big square green link icon" style="font-size: 2.8em; padding-right: 33.59px; margin-top: -5px"></i>
              </div>

              <div class="required field fourteen wide column" style="padding-left: 50px; padding-top: 10px;">
                <label style="text-align: left">Répertoire</label>
                <div class="ui left icon input">
                  <i class="server icon"></i>
                  <input type="text" v-bind:id="directory_path" v-model="directory.path" placeholder="Répertoire">
                </div>
              </div>
              <div class="required field fourteen wide column" style="padding-left: 50px; padding-top: 10px;">
                <label style="text-align: left">Options</label>
                <div class="ui left icon input">
                  <i class="server icon"></i>
                  <input type="text" v-bind:id="directory_options" v-model="directory.options" placeholder="ex: Options Indexes FollowSymLinks MultiViews">
                </div>
              </div>
              <div class="required field fourteen wide column" style="padding-left: 50px; padding-top: 10px;">
                <label style="text-align: left">AllowOverride</label>
                <div class="ui left icon input">
                  <i class="server icon"></i>
                  <input type="text" v-bind:id="directory_over" v-model="directory.over" placeholder="ex: AllowOverride None">
                </div>
              </div>
              <div class="field fourteen wide column" style="padding-left: 50px; padding-top: 10px;">
                <label style="text-align: left">Order</label>
                <div class="ui left icon input">
                  <i class="server icon"></i>
                  <input type="text" v-bind:id="directory_order" v-model="directory.order" placeholder="ex: Order allow,deny">
                </div>
              </div>
              <div class="field fourteen wide column" style="padding-left: 50px; padding-top: 10px;">
                <label style="text-align: left">Deny</label>
                <div class="ui left icon input">
                  <i class="server icon"></i>
                  <input type="text" v-bind:id="directory_deny" v-model="directory.deny" placeholder="ex: Deny from all">
                </div>
              </div>
              <div class="field fourteen wide column" style="padding-left: 50px; padding-top: 10px;">
                <label style="text-align: left">Allow</label>
                <div class="ui left icon input">
                  <i class="server icon"></i>
                  <input type="text" v-bind:id="directory_allow" v-model="directory.allow" placeholder="ex: Allow from 127.0.0.0/255.0.0.0 ::1/128">
                </div>
              </div>
						</div>

						<transition-group class="sixteen wide column " name="bounce" tag="div">
							<div class="ui grid" v-for="(domain_name, index) in bonus_directory" v-bind:key="index">
								<div class="required field fourteen wide column">
									<label style="text-align: left">Directory-{{ index + 2  }}</label>
									<div class="ui left icon input">
										<i class="server icon"></i>
										<input type="text" v-bind:id="'directory_' + index + '.path'" v-model="bonus_directory[index].path" placeholder="Domain-name-server">
									</div>
								</div>
								<div class="two wide column">
									<i class="minus big square red link icon" @click="removeOneDirectory(index)" style="font-size: 2.8em; padding-right: 33.59px; margin-top: 20px"></i>
								</div>
							</div>
						</transition-group>
					</div>
				</transition>

        <!--Logs-->
				<div class="ui divider" style="margin-top: 40px"></div>

        <div class="ui two column stackable centered grid container">
					<div class="left floated column">
						<span class="ui grey button" v-bind:class=" { 'grey': !logs_param, 'brown': logs_param } " @click="showLogs()">Logs</span>
					</div>
					<div class="right aligned column">
						<i class="circular inverted teal link icon"
						v-bind:class=" { 'angle double down': !logs_param, 'angle double up': logs_param, 'grey': !logs_param, 'brown': logs_param } " @click="showLogs()"></i>
					</div>
				</div>

				<transition name="bounce">
					<div class="ui grid" v-show="logs_param" style="padding-top: 30px;">
            <div class="ui stacked secondary sixteen wide column">
              <div class="required field">
                <label style="text-align: left">Log d'erreur</label>
                <div class="ui left icon input">
                  <i class="cube icon"></i>
                  <input type="text" name="errorLog" id="errorLog" v-model="errorLog" placeholder="Chemin du fichier errorLog">
                </div>
              </div>
              <div class="field">
                <label style="text-align: left">Log Level</label>
                <div class="ui left icon input">
                  <i class="cube icon"></i>
                  <input type="text" name="logLevel" id="logLevel" v-model="logLevel" placeholder="logLevel">
                </div>
              </div>
              <div class="required field">
                <label style="text-align: left">Log personnaliser</label>
                <div class="ui left icon input">
                  <i class="cube icon"></i>
                  <input type="text" name="customLog" id="customLog" v-model="customLog" placeholder="Chemin du fichier customLog">
                </div>
              </div>
						</div>
					</div>
				</transition>

				<div class="ui divider" style="margin-top: 40px"></div>

				<div class="ui grid" style="padding-top: 30px;">
					<div class="six column row">
						<div class="column"></div>
						<button class="ui green button" @click="">Save changes</button>
						<div class="column"></div>
						<div class="column"></div>
						<router-link class="ui red button" :to="{name: 'web'}">Cancel</router-link>
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

		name: 'Web_editvirtualhost',

		data () {
			return {
				loading: false,
				directories: false,
				logs_param: false,
				'ipaddress_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}' }]
				},
				'serverAdmin_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}'}]
				},
				'serverName_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},
        'serverAlias_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},
        'docRoot_form': {
          rules: [{type   : 'empty', // A APPROFONDIR
          prompt : 'Veuillez remplir correctement le champs {name}'}]
        },/* todo
        'alias_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},*/

        'directory_path_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt: 'Veuillez remplir correctement le champs {name}'}]
				},
				'directory_order_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},
        'directory_options_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},
        'directory_over_form': {
          rules: [{type   : 'empty', // A APPROFONDIR
          prompt : 'Veuillez remplir correctement le champs {name}'}]
        },
        'directory_allow_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},
        'directory_deny_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},


        'errorLog_form': {
          rules: [{type   : 'empty', // A APPROFONDIR
          prompt : 'Veuillez remplir correctement le champs {name}'}]
        },/* todo
        'logLevel_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},*/
        'customLog_form': {
					rules: [{type   : 'empty', // A APPROFONDIR
					prompt : 'Veuillez remplir correctement le champs {name}'}]
				},

				ipaddress: "",
				serverAdmin: "",
				serverName: "",
        serverAlias: "",
        docRoot: "",
        alias: "",

        errorLog: "",
        customLog: "",
        logLevel: "",

				directory: {},
				bonus_directory: [],

        conf: {},
			};
		},

		methods: {

			initForm() {
				$('#aditvirtualhostweb').form('destroy');
				let _this = this;

				var fields = {
					fields: {
						ipaddress: this.ipaddress_form,
						serverAdmin: this.serverAdmin_form,
						serverName: this.serverName_form,
            serverAlias: this.serverAlias_form,
            docRoot: this.docRoot_form,
            alias: this.alias_form,

            /* todo
            directory.path: this.directory_path_form,
            directory.order: this.directory_order_form,
            directory.over: this.directory_over_form,
            directory.allow: this.directory_allow_form,
            directory.deny: this.directory_deny_form,
            direction.options: this.directory_options_form,
            */

            errorLog: this.errorLog_form,
            logLevel: this.logLevel_form,
            customLog: this.customLog_form
					},

					onSuccess(event, field) {
						event.preventDefault();
						_this.editVirtualhost();
					}

				};

				if (this.directories === true) {
					fields.fields['directory.path'] = {
						rules: [{type   : 'empty', // A APPROFONDIR
						prompt : 'Veuillez remplir correctement le champs {name}'}]
					};
          fields.fields['directory.options'] = {
            rules: [{type   : 'empty', // A APPROFONDIR
            prompt : 'Veuillez remplir correctement le champs {name}'}]
          };
          fields.fields['directory.over'] = {
						rules: [{type   : 'empty', // A APPROFONDIR
						prompt : 'Veuillez remplir correctement le champs {name}'}]
					};
          fields.fields['directory.allow'] = {
						rules: [{type   : 'empty', // A APPROFONDIR
						prompt : 'Veuillez remplir correctement le champs {name}'}]
					};
          fields.fields['directory.deny'] = {
						rules: [{type   : 'empty', // A APPROFONDIR
						prompt : 'Veuillez remplir correctement le champs {name}'}]
					};
          fields.fields['directory.order'] = {
            rules: [{type   : 'empty', // A APPROFONDIR
            prompt : 'Veuillez remplir correctement le champs {name}'}]
          };

					for (var i = 0; i < this.bonus_directory.length; i++) {
						fields.fields['domain_name_' + i] = {
							rules: [{type   : 'regExp[^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$]', // A APPROFONDIR
							prompt : 'Veuillez remplir correctement le champs {name}'}]
						};

					}
				}
				$('#aditvirtualhostweb').form(fields);
			},

			showDirectory() {
				this.directories = !this.directories;
				this.$nextTick(function () {
					this.initForm();
				})
			},

			showLogs() {
				this.logs_param = !this.logs_param;
				this.$nextTick(function () {
					this.initForm();
				})
			},

			add_new_directory() {
				this.bonus_directory.push("");
				this.$nextTick(function () {
					this.initForm();
				})
			},

			removeOneDirectory(index) {
				this.bonus_directory.splice(index, 1)
				this.$nextTick(function () {
					this.initForm();
				})
			},

			editVirtualhost() {
        console.log("pass");
				this.loading = true;

				let domain_name_array = [];
        let newconf = {
          "ip": this.ipaddress,
          "ServerAdmin": this.serverAdmin,
          "ServerName": this.serverName,
          "ServerAlias": this.serverAlias,
          "DocumentRoot": this.docRoot,
          "Directory-0": {
              "0": this.directory.options,
              "1": this.directory.over,
              "2": this.directory.order,
              "3": this.directory.deny,
              "4": this.directory.allow,
              "path": this.directory.path
          },
          "ErrorLog": this.errorLog,
          "LogLevel": this.logLevel,
          "CustomLog": this.customLog,
          "Alias": this.alias
        };
        console.log(newconf);
        this.loading = false;

				if (this.directories) {
				}

        this.$http.post('web/vhost/conf/edit', {"ip": this.get_servers[this.$route.params.id].ip, "server_name": this.$route.params.host.server_name, "conf": newconf} ).then((response) => {
          this.loading = false;
          this.$router.push({ name: 'web' });
          console.log(response);
        },
        (response) => {
          console.log("error editvhostconf - ", response);
          this.loading = false;
        });
			},

      getVirtualhostConf() {
        this.loading = true;
        this.conf = {};
        this.$http.post('web/vhost/conf', {"ip": this.get_servers[this.$route.params.id].ip, "server_name": this.$route.params.host.server_name} ).then((response) => {
          this.loading = false;
          this.conf = response.body.response;
          this.ipaddress = this.conf.ip;
          this.serverAdmin = this.conf.ServerAdmin;
          this.serverName = this.conf.ServerName;
          this.serverAlias = this.conf.ServerAlias;
          this.docRoot = this.conf.DocumentRoot;
          this.alias = this.conf.Alias;

          this.errorLog = this.conf.ErrorLog;
          this.customLog = this.conf.CustomLog;
          this.logLevel = this.conf.LogLevel;

          this.directory = {
            "path": this.conf["Directory-0"]["path"],
            "options": this.conf["Directory-0"]["0"],
            "over": this.conf["Directory-0"]["1"],
            "order": this.conf["Directory-0"]["2"],
            "deny": this.conf["Directory-0"]["3"],
            "allow": this.conf["Directory-0"]["4"]
          },
          console.log(this.directory);
        },
        (response) => {
          console.log("error getvhostconf - ", response);
          this.loading = false;
        });
      }

		},

		computed: {
			...Vuex.mapGetters(['get_servers'])
		},

		mounted: function() {
			var _this = this;
			this.initForm();
      this.getVirtualhostConf();
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
