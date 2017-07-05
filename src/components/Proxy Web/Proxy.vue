<template>
	<div class="ui segment">
		<div class="ui left aligned grid row">

			<div v-bind:class="{ 'active': requestState.pools == false, 'active': requestState.members == false }" class="ui dimmer">
				<div class="ui indeterminate text loader">Fetching data</div>
			</div>

			<div class="ui horizontal divider" style="padding-top: 50px; padding-bottom: 50px">
				{{serverName}} | PROXY WEB
			</div>
			<div class="six wide column" style="padding-bottom: 20px; border-radius: 0px; border-bottom: 1px solid #ccc">
				<div class="ui top attached tabular menu">
					<a class="item active" data-tab="pools">Liste des Pools</a>
				</div>
				<div class="ui bottom attached tab segment active" data-tab="pools">
					<div class="ui middle aligned divided list" v-if="targeted_pools.length > 0">
						<div class="item" v-for="(pool, index) in serverPools" v-bind:class=" { 'target_color': targeted_pools[index].targeted }" @click="target_pool(index)">
							<i class="folder icon"></i>
							<div class="content">
								<a class="header">{{pool.name}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="two wide column" style="border-radius: 0px; border-left: 1px solid #ccc; padding-bottom: 30px">
				<div style="margin-top: 30%;">
					<div class="row">
						<button class="ui green button large" @click="addPool()" style="width: 100%; font-size: 0.8em">Ajouter</button>
					</div>
					<div class="row">
						<button class="ui orange button large" @click="editPool()" style="width: 100%; font-size: 0.8em">Editer</button>
					</div>
					<div class="row">
						<button class="ui red button" style="width: 100%; font-size: 0.8em" @click="deletePool()">Supprimer</button>
					</div>
				</div>
			</div>

      <div class="six wide column" style="padding-bottom: 20px; border-radius: 0px; border-bottom: 1px solid #ccc">
				<div class="ui top attached tabular menu">
					<a class="item active" data-tab="pools">Liste des membres du pools selectionner</a>
				</div>
				<div class="ui bottom attached tab segment active" data-tab="members">
					<div class="ui middle aligned divided list" v-if="targeted_members.length > 0">
						<div class="item" v-for="(member, index) in serverPoolMember" v-bind:class=" { 'target_color': targeted_members[index].targeted }" @click="target_member(index)">
							<i class="ui user icon"></i>
							<div class="content">
								<a class="header">{{member.name}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="two wide column" style="border-radius: 0px; border-left: 1px solid #ccc; padding-bottom: 30px">
				<div style="margin-top: 30%;">
					<div class="row">
						<button class="ui green button large" @click="addMember()" style="width: 100%; font-size: 0.8em">Ajouter</button>
					</div>
					<div class="row">
						<button class="ui orange button large" @click="editMember()" style="width: 100%; font-size: 0.8em">Editer</button>
					</div>
					<div class="row">
						<button class="ui red button" style="width: 100%; font-size: 0.8em" @click="deleteMember()">Supprimer</button>
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

		name: 'Proxy',

		data () {
			return {
        requestState: {"pools": false, "members": false},
        id_target_pool: -1,
        id_target_member: -1,
        targeted_pools: [],
        targeted_members: [],
        pools: [],
        members: []
			};
		},

    mounted: function() {
      this.get_proxy_config();
    },

    methods: {

      get_proxy_config: function() {
        this.requestState.pools = false;
        this.pools = [];
        this.members = [];
        this.$http.post('proxy/pools', {"ip": this.get_servers[this.$route.params.id].ip } ).then((response) => {
          this.pools = response.body.response;
          this.requestState.pools = true;
          this.requestState.members = true;
          for (var i = 0; i < this.pools.length; i++) {
            this.targeted_pools.push( {"targeted" : false});
          }
        },
        (response) => {
          console.log("error get proxy config  - ", response);
          this.requestState.pools = true;
          this.requestState.members = true;
        });
      },

      target_pool: function(index) {
        if (this.id_target_pool == -1) {
          this.targeted_pools[index].targeted = true;
          this.id_target_pool = index;
        } else {
          this.targeted_pools[this.id_target_pool].targeted = false;
          this.targeted_pools[index].targeted = true;
          this.id_target_pool = index;
        }
        this.requestState.members = false;
        this.members = [];
        this.$http.post('proxy/pools/members', {"ip": this.get_servers[this.$route.params.id].ip, "name_pool": this.pools[index].name } ).then((response) => {
          this.members = response.body.response;
          this.requestState.members = true;
          for (var i = 0; i < this.members.length; i++) {
            this.targeted_members.push( {"targeted" : false});
          }
        },
        (response) => {
          console.log("error get proxy config  - ", response);
          this.requestState.members = true;
        });
        console.log(this.pools[index]);
      },

      target_member: function(index) {
        if (this.id_target_member == -1) {
          this.targeted_members[index].targeted = true;
          this.id_target_member = index;
        } else {
          this.targeted_members[this.id_target_member].targeted = false;
          this.targeted_members[index].targeted = true;
          this.id_target_member = index;
        }
      },

      addPool: function() {
				if (this.id_target_pool == -1) {
					return;
				}
				this.$router.push({ name: 'proxy.addpool' });
      },

      editPool: function() {
				if (this.id_target_pool == -1) {
					return;
				}
				this.$router.push({name: 'proxy.editpool', params: {"ip_pool": this.pools[this.id_target_pool].ip, "pool_name": this.pools[this.id_target_pool].name}});
      },

      deletePool: function() {
        if (this.id_target_pool == -1) {
          return;
        }
        this.requestState.pools = false;
        this.$http.post('proxy/pools/delete', {"ip": this.get_servers[this.$route.params.id].ip, "pool_name": this.pools[this.id_target_pool].name} ).then((response) => {
					this.get_proxy_config();
					this.requestState.pools = true;
        },
        (response) => {
          console.log("error delete pool proxy  - ", response);
          this.requestState.pools = true;
        });
      },

      addMember: function() {
				if (this.id_target_pool == -1 ) {
					return;
				}
				let info = {
					"ip_pool": this.pools[this.id_target_pool].ip,
					"pool_name": this.pools[this.id_target_pool].name,
				};

				this.$router.push({name: 'proxy.addmember', params: {"info": info }});
      },

      editMember: function() {
				if (this.id_target_pool == -1 || this.id_target_member == -1) {
					return;
				}
				let info = {
					"ip_pool": this.pools[this.id_target_pool].ip,
					"pool_name": this.pools[this.id_target_pool].name,
					"member_name": this.members[this.id_target_member].name,
					"member_ip": this.members[this.id_target_member].ip
				};
				this.$router.push({name: 'proxy.editmember', params: {"info": info }});
      },

      deleteMember: function() {
        if (this.id_target_pool == -1 || this.id_target_member == -1) {
          return;
        }
        this.requestState.pools = false;
        this.$http.post('proxy/pools/member/delete', {"ip": this.get_servers[this.$route.params.id].ip, "name_pool": this.pools[this.id_target_pool].name, "name_server": this.members[this.id_target_member].name} ).then((response) => {
					this.get_proxy_config();
					this.requestState.members = true;
        },
        (response) => {
          console.log("error delete pool proxy  - ", response);
          this.requestState.members = true;
        });
      }
    },

    computed: {
      ...Vuex.mapGetters(['get_servers']),

      serverName: function() {
        this.get_servers[this.$route.params.id].hostname;
        return this.get_servers[this.$route.params.id].hostname;
      },

      serverPools: function() {
        return this.pools;
      },

      serverPoolMember: function() {
        return this.members;
      }
    }

  };

</script>

<style lang="css" scoped>
	.target_color {
		background-color: rgba(192,192,192, 0.5);
	}
</style>
