<template>
	<div class="ui segment">
		<div class="ui left aligned grid row">

			<div v-bind:class="{ 'active': requestState == false }" class="ui dimmer">
				<div class="ui indeterminate text loader">Fetching data</div>
			</div>

			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				Liste des machines pour les vérifications | Supervision
			</div>


			<div class="fourteen wide column" style="padding-bottom: 20px; border-radius: 0px; border-bottom: 1px solid #ccc">
				<div class="ui top attached tabular menu">
					<a class="item active" data-tab="passive">Passif</a>
					<a class="item" data-tab="active">Actif</a>
				</div>
				<div class="ui bottom attached tab segment active" data-tab="passive">
					<div class="ui middle aligned divided list">
						<div class="item" v-for="(server, index) in passive_server" v-bind:class=" { 'target_color': server.targeted == true }" @click="target_passive(index)">
							<i class="ui avatar user icon"></i>
							<div class="content">
								<a class="header">{{server.name}}</a>
							</div>
						</div>
					</div>
				</div>
				<div class="ui bottom attached tab segment" data-tab="active">
					<div class="ui middle aligned divided list">
						<div class="item" v-for="(server, index) in active_server" v-bind:class=" { 'target_color': server.targeted == true }" @click="target_server(index)">
							<i class="folder icon" ></i>
							<div class="content">
								<a class="header">{{server.name}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="two wide column" style="border-radius: 0px; border-left: 1px solid #ccc; padding-bottom: 30px">
				<div style="margin-top: 30%;">
					<div class="row">
						<router-link class="item" :to="{name: 'supervision.addserver' }"><button class="ui green button large" style="width: 100%; font-size: 0.8em">Ajouter</button></router-link>
					</div>
					<div class="row">
						<button class="ui red button" style="width: 100%; font-size: 0.8em">Supprimer</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {

		name: 'Supervision',

		data () {
			return {
				id_target_passive: -1,
				id_target_active: -1,
				current_tab: 0,
				passive_server : [{name: "192.168.1.1", targeted: false}, {name: "srv-zabbix.eloryal.fr", targeted: false }, {name: "127.0.0.1", targeted: false}],
				active_server : [{name: "192.168.1.3", targeted: false}, {name: "srv-zabbix2.eloryal.fr", targeted: false }, {name: "127.0.0.1", targeted: false}],

			};
		},

		mounted: function() {
			var _this = this;
			$('.menu .item').tab();
			$('.menu .item').tab({'onVisible':function(tabPath){ tabPath == 'passive' ? _this.current_tab = 0 : _this.current_tab = 1;}});
		},

		methods: {
			target_passive: function(index) {
				if (this.id_target_passive == -1) {
					this.passive_server[index].targeted = true;
					this.id_target_passive = index;
				} else {
					this.passive_server[this.id_target_passive].targeted = false;
					this.passive_server[index].targeted = true;
					this.id_target_passive = index;
				}
			},
			target_server: function(index) {
				if (this.id_target_active == -1) {
					this.active_server[index].targeted = true;
					this.id_target_active = index;
				} else {
					this.active_server[this.id_target_active].targeted = false;
					this.active_server[index].targeted = true;
					this.id_target_active = index;
				}
			},
		}
	};
</script>

<style lang="css" scoped>
	.target_color {
		background-color: rgba(192,192,192, 0.5);
	}
</style>