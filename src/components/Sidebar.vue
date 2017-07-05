<template>
	<div class="ui vertical visible inverted menu three wide column sidebar">
		<div class="ui floating dropdown fluid labeled icon button" style="margin-left: 5px" id="filter_service">
			<i class="filter icon"></i>
			<span class="text">Filter Posts</span>
			<div class="menu">
				<div class="ui icon search input">
					<i class="search icon"></i>
					<input type="text" placeholder="Search tags...">
				</div>
				<div class="divider"></div>
				<div class="header">
					<i class="tags icon"></i>
					Tag Label
				</div>
				<div class="scrolling menu">
					<div class="item" @click="setFilterService('ALL')">
						<div class="ui empty circular label" ></div>
						All services
					</div>
					<div class="item" @click="setFilterService('DNS')">
						<div class="ui red empty circular label"></div>
						DNS service
					</div>
					<div class="item" @click="setFilterService('DHCP')">
						<div class="ui blue empty circular label"></div>
						DHCP service
					</div>
					<div class="item" @click="setFilterService('SFTP')">
						<div class="ui orange empty circular label"></div>
						SFTP service
					</div>
					<div class="item" @click="setFilterService('SUPERVISION')">
						<div class="ui green empty circular label"></div>
						Supervision service
					</div>
					<div class="item" @click="setFilterService('MAJ')">
						<div class="ui purple empty circular label"></div>
						MAJ service
					</div>
					<div class="item" @click="setFilterService('Web')">
						<div class="ui brown empty circular label"></div>
						Web service
					</div>
					<div class="item" @click="setFilterService('PROXY')">
						<div class="ui teal empty circular label"></div>
						Proxy Web service
					</div>
					<div class="item" @click="setFilterService('TICKET')">
						<div class="ui grey empty circular label"></div>
						Ticketing service
					</div>
					<div class="item" @click="setFilterService('INVENTORY')">
						<div class="ui pink empty circular label"></div>
						Inventory service
					</div>
				</div>
			</div>
		</div>
		<div class="item">
			<div class="header"><router-link exact :to="{name: 'home'}">Eloryal</router-link></div>
			<div class="menu">
				<router-link class="item" :to="{name: 'add server'}">Ajouter un serveur</router-link>
				<router-link class="item" :to="{name: 'postman'}">Postman like</router-link>
			</div>
		</div>
		<div class="ui inverted segment" style="padding: 0px; margin: 0px">
			<div class="ui inverted accordion" id="accordion_sidebar_servers">
				<draggable v-model="filteredServers">
					<div v-for="server in filteredServers" class="item list-group" :key="server.id" style="list-style: none; padding-left: 3px; padding-top: 6px; padding-bottom: 6px">
						<div class="title">
							<i class="dropdown icon"></i>
							<div class="ui horizontal label" v-bind:class="{ 'red': server.service == 'DNS', 'blue' : server.service == 'DHCP', 'orange' : server.service == 'SFTP',  'green' : server.service == 'SUPERVISION', 'purple' : server.service == 'MAJ', 'brown' : server.service == 'WEB' , 'teal' : server.service == 'PROXY' , 'grey' : server.service == 'TICKET' , 'pink' : server.service == 'INVENTORY' , 'white' : server.service == 'NONE'}" style="font-size: 0.8em">{{ server.service }}
						</div>
						<span  v-bind:class="{ 'DNScolor': server.service == 'DNS',  'DHCPcolor': server.service == 'DHCP', 'SFTPcolor' : server.service == 'SFTP', 'SUPERVISONcolor' : server.service == 'SUPERVISION', 'MAJ' : server.service == 'MAJ', 'WEBcolor' : server.service == 'WEB', 'PROXYcolor' : server.service == 'PROXY', 'TICKETcolor' : server.service == 'TICKET', 'INVENTORYcolor' : server.service == 'INVENTORY'}">{{ server.hostname }}</span>
					</div>
					<div class="content" style="padding-top: 0px">

						<router-link class="item" :to="{name: 'network', params : {id: getServerIdx(server) }}">Network</router-link>
						<router-link class="item" v-if="server.service == 'DNS'" :to="{name: 'dns', params : {id: getServerIdx(server) }}">DNS</router-link>
						<router-link class="item" v-if="server.service == 'DHCP'" :to="{name: 'dhcp', params : {id: getServerIdx(server) }}">DHCP</router-link>
						<router-link class="item" v-if="server.service == 'SFTP'" :to="{name: 'sftp', params : {id: getServerIdx(server) }}">SFTP</router-link>
						<router-link class="item" v-if="server.service == 'MAJ'" :to="{name: 'maj', params : {id: getServerIdx(server) }}">Maj</router-link>

						<router-link class="item" v-if="server.service == 'WEB'" :to="{name: 'web', params : {id: getServerIdx(server) }}">Web</router-link>
						<router-link class="item" v-if="server.service == 'TICKET'" :to="{name: 'ticketing', params : {id: getServerIdx(server) }}">Ticket</router-link>
						<router-link class="item" v-if="server.service == 'INVENTORY'" :to="{name: 'inventory', params : {id: getServerIdx(server) }}">Inventory</router-link>
						<router-link class="item" v-if="server.service == 'PROXY'" :to="{name: 'proxy', params : {id: getServerIdx(server) }}">Proxy Web</router-link>

						<div class="item list-group" v-if="server.service == 'SUPERVISION' ? true : false" style="padding-left: 3px; padding-top: 6px; padding-bottom: 6px; margin: 0px">
							<div class="accordion" style="padding-top: 0px; padding-bottom: 2px; margin: 0px">
								<div class="title">
									<i class="dropdown icon"></i>
									Supervision
								</div>
								<div class="content" style="padding-top: 0px">
									<p class="transition visible">
										<router-link class="item":to="{name: 'supervision', params : {id: getServerIdx(server) }}">List machines</router-link>
										<router-link class="item":to="{name: 'supervision.config', params : {id: getServerIdx(server) }}">Configuration</router-link>
										<router-link class="item":to="{name: 'supervision.agents', params : {id: getServerIdx(server) }}">Agents</router-link>
									</p>
								</div>
							</div>
						</div>

						<router-link class="item" :to="{name: 'settings', params : {id: getServerIdx(server) }}">Settings</router-link>

					</div>
				</div>
			</draggable>
		</div>
	</div>
	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../store'
	import draggable from 'vuedraggable'

	export default {
		name: 'sidebar',

		components: {
			draggable,
		},

		data () {
			return {
				serviceFilter: "ALL"
			};
		},

		methods: {
			...Vuex.mapActions(['add_Server', 'clean']),

			retrieveServers : function () {
				this.$store.dispatch("loadServers").then(response => {
				}, error => {
					console.log("failure - ", error);
				})
			},

			setFilterService: function (service) {
				this.serviceFilter = service;
			},

			getServerIdx: function (thisServer) {
				return this.get_servers.findIndex((server) => {
					return server.hostname == thisServer.hostname;
				})
			}
		},

		computed: {
			...Vuex.mapGetters(['get_servers']),

			filteredServers: function() {
				if (this.serviceFilter === 'ALL') {
					return this.get_servers;
				} else {
					return this.get_servers.filter(server => {
						if (server.service === this.serviceFilter)
							return server;
					});
				}
			}
		},

		mounted: function() {
			$('#filter_service').dropdown('set text', '<div class=\"ui empty circular label\"></div>	All services');
			$('#accordion_sidebar_servers').accordion({'exclusive': false});
		},

		created: function() {
			this.clean();			// clean old servers before hotreloading
			this.retrieveServers();
		}
	}
</script>
<style lang="css" scoped>
	.DNScolor {
		color: #ff9999;
	}

	.DHCPcolor {
		color: #80bfff;
	}

	.SFTPcolor {
		color: #F2711C;
	}

	.SUPERVISONcolor {
		color: #21BA45;
	}

	.MAJ {
		color: #b870d0;
	}

	.WEBcolor {
		color: #A5673F;
	}

	.PROXYcolor {
		color: #00b5ad;
	}

	.INVENTORYcolor {
		color: #e03997;
	}

	.TICKETcolor {
		color: #767676;
	}

	.navbar {
		overflow-y: auto;
		overflow-x: hidden;
		font-size: 10px;
		box-shadow: 2px 0px 0px 0 rgba(34,36,38,.15);
		padding-top: 0px;
		padding-right: 0px;
		padding-left: 0px;
		margin-right: 0px;
		margin-left: 0px;
		color: white;
	}

	.clickable {
		cursor:pointer;
	}
	.clickable:hover {

	}
	.ui.secondary.vertical.pointing.menu {
		border-bottom-width: 0;
		border-right-width: 2px;
		/* border-right-style: solid; */
		/* border-right-color: rgba(34,36,38,.15); */
	}
	@media only screen and (max-width: 500px) {
		.stackable.grid:before {
			width: 100%;
			left: 0em;
		}
	}

	.style {
		box-shadow: 1px 1px 2px #888888; padding-right: 20px
	}

	.ui.selection {
		width: 5px;
	}

	.sidebar {
		background-color: #333;
		height: 100%;
		overflow-y: auto;
		overflow-x: auto;
	}

</style>
