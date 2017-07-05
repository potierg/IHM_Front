<template>
	<div class="ui segment">
		<div v-bind:class="{ 'active': requestState == false }" class="ui dimmer">
			<div class="ui indeterminate text loader">Fetching data</div>
		</div>
		<p></p>
		<div class="ui horizontal divider" style="padding-top: 2%">
			{{serverName}} | WEB
		</div>

    <!--VIRTUALHOST-->
		<div class="ui grid" style="padding-bottom: 20px; padding-top: 50px;">
			<div class="left aligned three wide column">
				<h3>Liste des virtualhost</h3>
			</div>
			<div class="middle aligned two wide column">
				<router-link class="item" :to="{name: 'web.addvirtualhost' }"><button class="ui primary button"><i class="add circle icon"></i>Ajouter</button></router-link>
			</div>
		</div>
		<table class="ui sortable celled table" id="tableZoneDns" style="margin-bottom: 50px">
			<thead>
				<tr>
					<th class="sorted descending">Nom de serveur</th>
					<th>Adresse IP</th>
					<th>Editer</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(host, index) in this.virtualhosts">
					<td>{{ host.server_name }}</td>
					<td>{{ host.ip }}</td>
					<td><router-link class="item" tag="span" :to="{name: 'web.editvirtualhost', params : {id: $route.params.id, host: host }}"><i class="big link settings icon" style="margin-left: 40%"></i></router-link></td>
					<td><i class="big link trash outline icon" style="margin-left: 40%" @click="deleteVirtualhost(index)"></i></td>
				</tr>
			</tbody>

			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</template>

<script>

	import Vuex from 'vuex'

	export default {

		name: 'Web',

		data () {
			return {
				requestState: false,
				virtualhosts: []
			};
		},

		mounted: function() {
			this.getVirtualHost();
		},

		methods: {

			getVirtualHost() {
				this.requestState = false;
				this.virtualhosts = [];
        this.$http.post('web/vhosts', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
          this.virtualhosts = response.body.response;
          this.requestState = true;
        },
        (response) => {
          console.log("error getVirtualHost web  - ", response);
          this.requestState = true;
        });

			},

			deleteVirtualhost(index) {
				this.requestState = false;
				this.$http.post('web/vhost/delete', {"ip": this.get_servers[this.$route.params.id].ip, "server_name": this.virtualhosts[index].server_name } ).then((response) => {
          this.virtualhosts = response.body.response;
					this.getVirtualHost();
        },
        (response) => {
          console.log("error getVirtualHost web  - ", response);
          this.requestState = true;
        });
			}
		},

		computed: {
			...Vuex.mapGetters(['get_servers']),

			serverName: function() {
				this.get_servers[this.$route.params.id].hostname;
				return this.get_servers[this.$route.params.id].hostname;
			}

		}
	};
</script>

<style lang="css" scoped>
</style>
