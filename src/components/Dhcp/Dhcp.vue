<template>
	<div class="ui segment">
		<div v-bind:class="{ 'active': requestState == false }" class="ui dimmer">
			<div class="ui indeterminate text loader">Fetching data</div>
		</div>
		<p></p>
		<div class="ui horizontal divider" style="padding-top: 2%">
			{{serverName}} | DHCP CONFIGURATION
		</div>
		<div class="ui grid" style="padding-bottom: 20px; padding-top: 50px;">
			<div class="middle aligned two wide column">
				<h3>Zone sous-réseau</h3>
			</div>
			<div class="middle aligned two wide column">
				<router-link class="item" :to="{name: 'dhcp.addzone' }"><button class="ui primary button"><i class="add circle icon"></i>Ajouter</button></router-link>
			</div>
		</div>
		<table class="ui sortable celled table" id="tableZoneDns" style="margin-bottom: 50px">
			<thead>
				<tr>
					<th class="sorted descending">IP réseau</th>
					<th class="">Range</th>
					<th>Editer</th>
					<th>Supprimer</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(subnet, index) in get_servers[this.$route.params.id].subnets">
					<td>{{ subnet.subnet }}</td>
					<td>{{ subnet.option.range.start }} - {{subnet.option.range.end }}</td>
					<td><router-link class="item" tag="span" :to="{name: 'dhcp.editzone', params : {id: $route.params.id, subnet: index }}"><i class="big link settings icon" style="margin-left: 44%"></i></router-link></td>
					<td><i class="big link trash outline icon" style="margin-left: 45%" @click="deleteSubnet(index)"></i></td>
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
		<div class="ui grid" style="padding-bottom: 20px;">
			<div class="left aligned two wide column" style="margin-top: 6px">
				<h3>Baux</h3>
			</div>
		</div>

		<table class="ui sortable celled table" id="tableBauxDns" style="margin-bottom: 50px">
			<thead>
				<tr>
					<th class="sorted descending">IP réseau</th>
					<th>Hardware Ethernet</th>
					<th>Hosname</th>
					<th>UID</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(item, index) in baux">
					<td>{{ item.ip }}</td>
					<td>{{ item.hardeth }}</td>
					<td>{{ item.hostname }}</td>
					<td>{{ item.uid }}</td>
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

		name: 'Dhcp',

		data () {
			return {
				requestState: false,
				baux: [],
			};
		},

		mounted: function() {
			this.getSubnets();
			this.getBaux();
		},

		methods: {
			...Vuex.mapActions(['get_Subnets', 'edit_Subnet', 'delete_Subnet']),

			getSubnets() {
				if (!this.get_servers[this.$route.params.id].hasOwnProperty("subnets")) {
					this.requestState = false;
					this.get_Subnets( this.get_servers[this.$route.params.id] ).then(response => {
						this.requestState = true;
						console.log(response, this.get_servers);

					}, error => {
						console.log(error);
						this.requestState = true;
					})
				} else {
					this.requestState = true;
				}
			},

			getBaux() {

				this.$http.post('dhcp/leases', {"ip": this.get_servers[this.$route.params.id].ip} ).then((response) => {

					let _this = this;
					if (response.body.response.length > 0) {
						$.each(response.body.response, function(index, value) {
							_this.baux.push({"ip": value["lease"], "hardeth": value["hardware ethernet"], "hostname": value["client-hostname"], "uid": value["uid"]});
						});
					}
					else
						console.log("dchp request did not return an array lease - array lease is empty.");
						this.requestState = true;
					},
					(response) => {
						console.log("error getLeaseDhcp - ", response);
						this.requestState = true;
				});

			},

			deleteSubnet(index) {
				this.requestState = false;

				this.delete_Subnet({"server": this.get_servers[this.$route.params.id], "idx_subnet": index}).then(response => {
					console.log(response);
					this.requestState = true;

				}, error => {
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