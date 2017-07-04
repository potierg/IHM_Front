  import store from './store'
  
  export default {
    checkValidIdUrl(to, next) {
      console.log("checkValidIdUrl");
      if (!(store.getters.get_servers == null || to.params.id >= store.getters.get_servers.length || store.getters.get_servers[to.params.id] == null))
       next();
     else {
      setTimeout(()=> {
        if (store.getters.get_servers != null && store.getters.get_servers.length >= to.params.id && store.getters.get_servers[to.params.id] != null) {
          next();
        } else {
          next({name : 'home'});
        }
      }, 1500);
    }
  },

  checkServiceAndId(to, next, service) {
    console.log("checkIfServerIs" + service);
    if (store.getters.get_servers != null && store.getters.get_servers != undefined && store.getters.get_servers.length > to.params.id && store.getters.get_servers[to.params.id] != undefined &&
     store.getters.get_servers[to.params.id].service === service)
      next();
    else
      next({name : 'home'});
  }
}