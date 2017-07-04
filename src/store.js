import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const state = {
	//loggedIn: false,
	servers: []
}

const mutations = {
	/*SET_LOGGEDIN: (state, logged) => {
		state.loggedIn = logged;
		localStorage.token = logged;
	},*/

	SET_SERVERS: (state, servers) => {
		state.servers = servers;
	},

	SET_SERVER: (state, payload) => {
		// payload.hostname | payload.service | payload.ip | payload.idx
		state.servers[payload.idx].hostname = payload.hostname;
		state.servers[payload.idx].service = payload.service.toUpperCase();
		state.servers[payload.idx].ip = payload.ip;
	},

	ADD_SERVER: (state, server) => {
		state.servers.push(server);
	},

	CLEAN: (state) => {
		state.servers = [];
	},

	DELETE_SERVER: (state, my_server) => {
		let idx;

		if ((idx = state.servers.findIndex((server) => {
			return server == my_server;
		})) != -1) {
			state.servers.splice(idx, 1);
		}
	},

	GET_SUBNETS: (state, payload) => {
		// payload.server | payload.subnets
		payload.server.subnets = payload.subnets;
		//Vue.set(payload.server, "subnets", payload.subnets);
	},

	ADD_SUBNET: (state, payload) => {
		// payload.server | payload.subnet
		if (!payload.server.hasOwnProperty("subnets"))
			Vue.set(payload.server, "subnets", []);
		payload.server.subnets.push(payload.subnet);
	},

	EDIT_SUBNET: (state, payload) => {
		// payload.server | payload.new_subnet | payload.idx_subnet
		payload.server.subnets[payload.idx_subnet] = payload.new_subnet;
	},

	DELETE_SUBNET: (state, payload) => {
		// payload.server | payload.idx_subnet
		payload.server.subnets.splice(payload.idx_subnet, 1);
	},

	ADD_SFTP_USER: (state, payload) => {
		//payload.server | payload.user
		if (!payload.server.hasOwnProperty("users"))
			Vue.set(payload.server, "users", []);
		payload.server.users.push(payload.user);
	},

 	DELETE_SFTP_USER: (state, payload) => {
		//payload.server | payload.id_user
		payload.server.users.splice(payload.id_user, 1);
	},

	DELETE_SFTP_DIRECTORY: (state, payload) => {
		//payload.server | payload.id_directory
		payload.server.shared_directories.splice(payload.id_directory, 1);

	},

	ADD_SFTP_SHARED_DIRECTORY: (state, payload) => {
		//payload.server | payload.shared_directory
		if (!payload.server.hasOwnProperty("shared_directories"))
			Vue.set(payload.server, "shared_directories", []);
		payload.server.shared_directories.push(payload.shared_directory);
		if (!payload.server.shared_directories[payload.server.shared_directories.length - 1].hasOwnProperty("associated_users"))
 			Vue.set(payload.server.shared_directories[payload.server.shared_directories.length - 1], "associated_users", []);
	},

	ADD_SFTP_USER_IN_DIRECTORY: (state, payload) => {
 		// payload.directory | payload.user
 		if (!payload.directory.hasOwnProperty("associated_users"))
 			Vue.set(payload.directory, "associated_users", []);
 		payload.directory.associated_users.push(payload.user);
 	},

	DELETE_SFTP_USER_IN_DIRECTORY: (state, payload) => {
		// payload.directory | payload.idx_user
		console.log(payload);
		if (payload.directory.hasOwnProperty("associated_users")) {
			payload.directory.associated_users.splice(payload.idx_user, 1);
		}
	}
}

const getters = {
	get_loggedIn: state => state.loggedIn,
	get_servers: state => state.servers
}

const actions = {
	loadServers: (state) => {
		return new Promise((resolve, reject) => {
			Vue.http.get('servers').then((response) => {
				resolve(response);
				for (var i = 0; i < response.body.response.length; i++) {
					store.commit('ADD_SERVER', {"hostname": response.body.response[i].name, "service": response.body.response[i].service.toUpperCase(), "ip": response.body.response[i].ip});
				};
			},
			(response) => {
				console.log("error retrieveServers function");
				reject(response);
			});
		})
	},

	/*set_LoggedIn: (state, logged) => {
		store.commit('SET_LOGGEDIN', logged)
	},*/

	set_Servers: (state, servers) => {
		store.commit('SET_SERVERS', servers);
	},

	set_Server: (state, server) => {
		store.commit('SET_SERVER', server);
	},

	add_Server: (state, server) => {
		store.commit('ADD_SERVER', server);
	},

	clean: (state) => {
		store.commit('CLEAN');
	},

	get_Subnets: (state, server) => {
		return new Promise((resolve, reject) => {
			Vue.http.post('dhcp/subnets', {"ip": server.ip } ).then((response) => {
				resolve(response);
				response.body.response.forEach( function(element, index) {
					if (element.hasOwnProperty("subnet") && element.subnet.length > 0)
						store.commit('ADD_SUBNET', { "server": server, "subnet": response.body.response[index] });
				});
			},
			(response) => {
				reject(response);
			});
		});
	},

	add_Subnet: (state, payload) => {
		// payload.server | payload.subnet
		return new Promise((resolve, reject) => {
			Vue.http.post('dhcp/subnets/add', {"ip": payload.server.ip, "subnet": payload.subnet} ).then((response) => {
				resolve(response);
				store.commit('ADD_SUBNET', payload);
			},
			(response) => {
				reject(response);
			});
		});
	},

	edit_Subnet: (state, payload) => {
		// payload.server | payload.new_subnet | payload.idx_subnet
		//console.log(payload);
		console.log( {"ip": payload.server.ip, "subnet": payload.new_subnet, "subnet_origin_ip": payload.server.subnets[payload.idx_subnet].subnet} );
		return new Promise((resolve, reject) => {
				Vue.http.post('dhcp/subnets/edit', {"ip": payload.server.ip, "subnet": payload.new_subnet, "subnet_origin_ip": payload.server.subnets[payload.idx_subnet].subnet} ).then((response) => {
					resolve(response);
					store.commit('EDIT_SUBNET', payload);
				},
				(response) => {
					reject(response);
				});
		});
	},

	delete_Subnet: (state, payload) => {
		// payload.server | payload.idx_subnet
		return new Promise((resolve, reject) => {
			Vue.http.post('dhcp/subnets/delete', {"ip": payload.server.ip, "subnet_ip": payload.server.subnets[payload.idx_subnet].subnet } ).then((response) => {
				resolve(response);
				store.commit('DELETE_SUBNET', payload);
			},
			(response) => {
				reject(response);
			});
		});
	},

	delete_Server: (state, server) => {
		return new Promise((resolve, reject) => {
			store.commit('DELETE_SERVER', server);
			resolve();
		})
	},

	get_SFTP_users: (state, server) => {
		return new Promise((resolve, reject) => {
			Vue.http.post('sftp/users', {"ip": server.ip } ).then((response) => {
				resolve(response);
				response.body.response.forEach( function(element, index) {
					
					store.commit('ADD_SFTP_USER', { "server": server, "user": response.body.response[index] });
				});
			},
			(response) => {
				reject(response);
			});
		});
	},	

	get_SFTP_sharedDirectories: (state, server) => {
		return new Promise((resolve, reject) => {
			Vue.http.post('sftp/folders', {"ip": server.ip } ).then((response) => {
				response.body.response.shared_folders.forEach( function(element, index) {
					
					store.commit('ADD_SFTP_SHARED_DIRECTORY', { "server": server, 
						"shared_directory": response.body.response.shared_folders[index] });
				});
				resolve(response);
			},
			(response) => {
				reject(response);
			});
		});
	},

	get_SFTP_usersInsharedDirectories: (state, payload) => {
		// payload.server | payload.directory | payload.idx_directory
		return new Promise((resolve, reject) => {
			Vue.http.post('sftp/shared_folders/users', { "ip": payload.server.ip, "name_folder": payload.directory } ).then((response) => {
				resolve(response);
				response.body.response.forEach( function(element, index) {
					store.commit("ADD_SFTP_USER_IN_DIRECTORY", { "directory": payload.server.shared_directories[payload.idx_directory], "user": element});
				});
			},
			(response) => {
				reject(response);
			});
		});
	},

	add_SFTP_users: (state, payload) => {
		// payload.server | payload.login | payload.passwd
		return new Promise((resolve, reject) => {
			console.log({"ip": payload.server.ip, "login": payload.login, "passwd": payload.passwd});
			Vue.http.post('sftp/users/add', {"ip": payload.server.ip, "login": payload.login, "passwd": payload.passwd} ).then((response) => {
				store.commit('ADD_SFTP_USER', {"server":  payload.server, "user": {"login": payload.login, "right": {}} });
				resolve(response);

			},
			(response) => {
				reject(response);
			});
		});
	},

	add_SFTP_usersInDirectory: (state, payload) => {
		// payload.server | payload.idx_directory | payload.login
		return new Promise((resolve, reject) => {
			Vue.http.post('sftp/shared_folders/users/add', {"ip": payload.server.ip, 
				"name_folder": payload.server.shared_directories[payload.idx_directory].name, "login": payload.login} ).then((response) => {
				resolve(response);
				store.commit("ADD_SFTP_USER_IN_DIRECTORY", { "directory": payload.server.shared_directories[payload.idx_directory], "user": payload.login});			},
			(response) => {
				reject(response);
			});
		});
	},

	delete_SFTP_usersInDirectory: (state, payload) => {
		// payload.server | payload.idx_directory | payload.idx_user | payload.login 
		console.log(payload, {"ip": payload.server.ip, 
				"name_folder": payload.server.shared_directories[payload.idx_directory].name, "login": payload.login});
		return new Promise((resolve, reject) => {
			Vue.http.post('sftp/shared_folders/users/delete', {"ip": payload.server.ip, 
				"name_folder": payload.server.shared_directories[payload.idx_directory].name, "login": payload.login} ).then((response) => {
				resolve(response);
				store.commit("DELETE_SFTP_USER_IN_DIRECTORY", { "directory": payload.server.shared_directories[payload.idx_directory], "idx_user": payload.idx_user});			},
			(response) => {
				reject(response);
			});
		});
	},

	add_SFTP_sharedDirectory: (state, payload) => {
		return new Promise((resolve, reject) => {
			Vue.http.post('sftp/shared_folders/add', { "ip": payload.server.ip, "name_folder": payload.directory} ).then((response) => {
				resolve(response);
				store.commit("ADD_SFTP_SHARED_DIRECTORY", { "server": payload.server, "shared_directory": { "name": payload.directory } } );
			},
			(response) => {
				reject(response);
			});
		});
	}
}

let store = new Vuex.Store ({
	state: state,
	mutations: mutations,
	getters: getters,
	actions: actions,
	strict: process.env.NODE_ENV !== 'production'
})

export default store