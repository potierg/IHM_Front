<template>
	<div class="ui segment">
		<div class="ui left aligned grid row">

			<div v-bind:class="{ }" class="ui dimmer">
				<div class="ui indeterminate text loader">Fetching data</div>
			</div>

			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				{{serverName}} | MAJ
			</div>


			<div class="fourteen wide column" style="padding-bottom: 20px; border-radius: 0px; border-bottom: 1px solid #ccc">
				<div class="ui top attached tabular menu">
					<a class="item active" data-tab="mirror">Mirroir local</a>
					<a class="item" data-tab="synchro">Dépôt synchronisé</a>
				</div>
				<div class="ui bottom attached tab segment active" data-tab="mirror">
					<div class="ui middle aligned divided list" v-if="targeted_mirror.length > 0">
						<div class="item" v-for="(mirror, index) in mirror_local" v-bind:class=" { 'target_color': targeted_mirror[index].targeted }" @click="target_mirror(index)">
							<i class="ui avatar user icon"></i>
							<div class="content">
								<a class="header">{{mirror.name}}</a>
							</div>
						</div>
					</div>
				</div>
				<div class="ui bottom attached tab segment" data-tab="synchro">
					<div class="ui middle aligned divided list" v-if="targeted_synchro.length > 0">
						<div class="item" v-for="(synchro, index) in depot_synchro" v-bind:class=" { 'target_color': targeted_synchro[index].targeted == true }" @click="target_synchro(index)">
							<i class="folder icon" ></i>
							<div class="content">
								<a class="header">{{synchro.name}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="two wide column" style="border-radius: 0px; border-left: 1px solid #ccc; padding-bottom: 30px">
				<div style="margin-top: 30%;">
					<div class="row">
						<button class="ui green button large" @click="addSomething()" style="width: 100%; font-size: 0.8em">Ajouter</button>
					</div>
					<div class="row">
						<button class="ui orange button large" @click="editSomething()" style="width: 100%; font-size: 0.8em">Editer</button>
					</div>
					<div class="row">
						<button class="ui red button" style="width: 100%; font-size: 0.8em" @click="deleteSomething()">Supprimer</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>

	import Vuex from 'vuex'
	import store from '../../store'

	export default {

		name: 'MAJ',

		data () {
			return {
				mirror_local: [{"name": "mirror toto"}, {"name": "mirror tata"}, {"name": "mirror tutu"}],
				depot_synchro: [{"name": "depot_synchro toto"}, {"name": "depot_synchro tata"}, {"name": "depot_synchro tutu"}],
				/* 0 = mirroir local / 1 = depot synchronisé */
				current_tab: 0,
				id_target_mirror: -1,
				id_target_synchro: -1,
				targeted_mirror: [],
				targeted_synchro: [],
			};
		},

		mounted: function() {
			var _this = this;

			$('.menu .item').tab();
			$('.menu .item').tab({'onVisible':function(tabPath){ tabPath == 'mirror' ? _this.current_tab = 0 : _this.current_tab = 1;}});

			for (var i = 0; i < this.mirror_local.length; i++) {
				this.targeted_mirror.push({"targeted": false});
			}
			for (var i = 0; i < this.depot_synchro.length; i++) {
				this.targeted_synchro.push({"targeted": false});
			}
			this.getInfo();
		},

		methods: {
			target_mirror: function(index) {
				if (this.id_target_mirror == -1) {
					this.targeted_mirror[index].targeted = true;
					this.id_target_mirror = index;
				} else {
					this.targeted_mirror[this.id_target_mirror].targeted = false;
					this.targeted_mirror[index].targeted = true;
					this.id_target_mirror = index;
				};
			},

			target_synchro: function(index) {
				if (this.id_target_synchro == -1) {
					this.targeted_synchro[index].targeted = true;
					this.id_target_synchro = index;
				} else {
					this.targeted_synchro[this.id_target_synchro].targeted = false;
					this.targeted_synchro[index].targeted = true;
					this.id_target_synchro = index;
				}
			},

			getInfo: function() {
				this.$http.post('update/get_repos', {"ip": this.get_servers[this.$route.params.id].ip} ).then((response) => {
					console.log(response.body.response);
				},
				(response) => {
					console.log("error addPoolMember - ", response);
				});
			}
		},

		computed: {
			...Vuex.mapGetters(['get_servers']),

			serverName: function() {
				this.get_servers[this.$route.params.id].hostname;
				return this.get_servers[this.$route.params.id].hostname;
			},
		}
	};
</script>

<style lang="css" scoped>
	.target_color {
		background-color: rgba(192,192,192, 0.5);
	}
</style>
