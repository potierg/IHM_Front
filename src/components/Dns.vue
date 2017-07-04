<template>
	<div class="ui segment">
		<button class="ui primary button" @click="checkConfigDns()">
			Check config DNS
		</button>
		<table class="ui sortable celled table" id="tableZoneDns" style="margin-bottom: 50px">
			<thead>
				<tr>
					<th class="sorted descending">Zone name</th>
					<th class="">Type</th>
					<th>File</th>
					<th>More info</th>
					<th>Check config zone</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(zone, index) in zonedns">
					<td>{{ zone.name }}</td>
					<td>{{ zone.type }}</td>
					<td> {{ zone.file }}</td>
					<td @click="moreInfoZoneDns(zone.name, index)">click</td>
					<td @click="checkConfigZone(zone.name, index)">click</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>


		<div class="ui horizontal divider">
			Or
		</div>
		<h2 class="ui image header">
			<div class="content">
				Edit {{serverName}} option
			</div>
		</h2>

		<div class="ui center aligned grid">
			<form v-on:submit.stop.prevent="editConfDns" class="ui form column" id="editConfDns" style="padding-top: 50px; max-width: 600px;">
				<div class="ui stacked secondary segment">
					<div class="required field">
						<label style="text-align: left">Directory</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="directorydns" id="directorydns" v-model="directorydns" placeholder="Directory">
						</div>
					</div>
					<div class="required field">
						<label style="text-align: left">Allow-query</label>
						<div class="ui left icon input">
							<i class="server icon"></i>
							<input type="text" name="allowquerydns" id="allowquerydns" v-model="allowquerydns" placeholder="192.0.0.1 192.0.0.2 ...">
						</div>
					</div>
					<button type="submit" class="ui fluid large teal button">Confirm</button>

				</div>
				<div class="ui error message"></div>

			</form>
		</div>



	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../store'


	export default {

		name: 'Dns',

		data () {
			return {
				directorydns: "",
				allowquerydns: "",
				zonedns: []
			};
		},

		mounted: function () {
			this.getConfDns();
			$('#tableZoneDns').tablesort();
			$('#editConfDns').form({
				fields: {
					directorydns: {
						rules: [
						{
							type   : 'empty',
							prompt : '{name} must have a value'
						}
						]
					}
				},

				onSuccess(event, field) {
					event.preventDefault();
				}
			})
		},

		methods: {
			getConfDns: function() {
				this.directorydns = "";
				this.allowquerydns = "";
				this.zonedns = [];
				let _this = this;
				this.$http.post('dns', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
					console.log(response);
					if (response.body.response.hasOwnProperty("zone")) {
					$.each(response.body.response.zone, function(index, value) {
						_this.zonedns.push({"name": value.name, "file": value.file, "type": value.type});
					}); 
				}
				else
					console.log("dns request did not return an array zone - array zone is empty.");
					this.requestState = true;
				},
				(response) => {
					console.log("error getConfDns - ", response);
					this.requestState = true;
				});
			},

			editConfDns() {
				if ($('#editConfDns').form("is valid")) {
					console.log( this.directorydns, this.allowquerydns.split(" "));
					this.$http.post('dns/edit', {"ip": this.get_servers[this.$route.params.id].ip, "conf": { "directory": this.directorydns, "allow-query": this.allowquerydns.split(" ") } } ).then((response) => {
						console.log(response);
						this.requestState = true;
					},
					(response) => {
						console.log("error editConfDns - ", response);
						this.requestState = true;
					});
				}
			},

			moreInfoZoneDns(nameZone, index) {
				console.log(this.get_servers[this.$route.params.id].ip, nameZone);
				this.$http.post('dns/zone/details', {"ip": this.get_servers[this.$route.params.id].ip, "zone_name": nameZone } ).then((response) => {
					console.log(response);
					this.requestState = true;
				},
				(response) => {
					console.log("error moreInfoZoneDns - ", response);
					this.requestState = true;
				});
			},

			checkConfigDns() {
				this.$http.post('dns/check', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
					console.log(response);
					this.requestState = true;
				},
				(response) => {
					console.log("error checkConfigDns - ", response);
					this.requestState = true;
				});
			},

			checkConfigZone(nameZone, index) {
				this.$http.post('dns/zone/check', {"ip": this.get_servers[this.$route.params.id].ip, "zone_name": nameZone} ).then((response) => {
					console.log(response);
					this.requestState = true;
				},
				(response) => {
					console.log("error checkConfigZone - ", response);
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
		},

		watch: {				  // when route changes and this component is already rendered
			'$route' (to, from) {
				if (store.getters.get_servers == null || store.getters.get_servers == undefined || to.params.id >= store.getters.get_servers.length || store.getters.get_servers[to.params.id] == null ||
					store.getters.get_servers[to.params.id].service != 'DNS') {
					console.log("redirected by $route dns");
					this.$router.push({name : 'home'});
			}
				else {
					this.getConfDns();
				}
			}
		}
	};
</script>

<style lang="css" scoped>
</style>