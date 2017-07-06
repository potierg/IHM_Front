import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import store from './store'
import redirect from './redirect'

Vue.use(VueRouter)
Vue.use(VueResource)

Vue.filter('capitalize', function (value){      // EXAMPLE FILTER UPPERCASE
  console.log(value);
  return value;
});

/*const redirectGuest = function (to, from, next) {
  if (localStorage.token === "true") {
    next();
  } else {
    next( { path : '/login' } );
  }
}*/

const router = new VueRouter ({
  mode: 'hash',
  routes: [
  { path: '/', component: require('./components/Home.vue'), name: 'home'}, ////////////////////// REQUIRE AUTH

  //{ path: '/login', component: require('./components/Login.vue'), name: 'log in'},

  //{ path: '/signup', component: require('./components/Signup.vue'), name: 'sign up'},

  { path: '/network/:id(\\d+)', component: require('./components/Network.vue'), name: 'network', beforeEnter: (to, from, next) => {redirect.checkValidIdUrl(to, next);} }, ///////// REQUIRE AUTH

  { path: '/addserver', component: require('./components/AddServer.vue'), name: 'add server'}, ///////// REQUIRE AUTH

  { path: '/settings/:id(\\d+)', component: require('./components/Settings.vue'), name: 'settings', beforeEnter: (to, from, next) => {redirect.checkValidIdUrl(to, next);} }, ///////// REQUIRE AUTH

  { path: '/dns/:id(\\d+)', component: require('./components/DNS/Dns_Group.vue'), beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "DNS")},
    children: [
      { path: '', component: require('./components/DNS/Dns.vue'), name: 'dns'},
      { path: 'addzone', component: require('./components/DNS/Dns_addzone.vue'), name: 'dns.addzone'},
      { path: 'editzone', component: require('./components/DNS/Dns_editzone.vue'), name: 'dns.editzone'}
    ]
  }, ///////// REQUIRE AUTH

  { path: '/dhcp/:id(\\d+)', component: require('./components/Dhcp/Dhcp_Group.vue'), beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "DHCP")},
  children: [
  { path: 'addzone', component: require('./components/Dhcp/Dhcp_AddZone.vue'), name: 'dhcp.addzone'},
  { path: 'editzone/:subnet(\\d+)', component: require('./components/Dhcp/Dhcp_EditZone.vue'), name: 'dhcp.editzone' },
  { path: '', component: require('./components/Dhcp/Dhcp.vue'), name: 'dhcp'},
  { path: 'editzone/:subnet(\\d+)/addhost', component: require('./components/Dhcp/Dhcp_AddHost.vue'), name: 'dhcp.addhost'},
  { path: 'editzone/:subnet(\\d+)/edithost', component: require('./components/Dhcp/Dhcp_EditHost.vue'), name: 'dhcp.edithost'}
  ]  }, ///////// REQUIRE AUTH



  { path: '/sftp/:id(\\d+)', component: require('./components/SFTP/SFTP_Group.vue'), beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "SFTP")},
  children: [
  { path: 'adduser', component: require('./components/SFTP/SFTP_adduser.vue'), name: 'sftp.adduser'},
  { path: 'edituser/:user(\\d+)', component: require('./components/SFTP/SFTP_edituser.vue'), name: 'sftp.edituser'},
  { path: 'adddirectory', component: require('./components/SFTP/SFTP_adddirectory.vue'), name: 'sftp.adddirectory'},
  { path: 'editdirectory', component: require('./components/SFTP/SFTP_editdirectory.vue'), name: 'sftp.editdirectory'},
  { path: '', component: require('./components/SFTP/SFTP.vue'), name: 'sftp'}
  ]  }, ///////// REQUIRE AUTH

   { path: '/supervision/:id(\\d+)', component: require('./components/Supervision/Supervision_Group.vue'), beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "SUPERVISION")},
  children: [
  { path: 'addserver', component: require('./components/Supervision/Supervision_addserver.vue'), name: 'supervision.addserver'},
  { path: 'config', component: require('./components/Supervision/Supervision_config.vue'), name: 'supervision.config'},
  { path: 'agent', component: require('./components/Supervision/Agents.vue'), name: 'supervision.agents'},
  { path: '', component: require('./components/Supervision/Supervision.vue'), name: 'supervision'}
  ]  }, ///////// REQUIRE AUTH

  { path: '/MAJ/:id(\\d+)', component: require('./components/MAJ/MAJ_Group.vue'), beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "MAJ")},
    children: [
      { path: '', component: require('./components/MAJ/MAJ.vue'), name: 'maj'},
      { path: 'addmirror', component: require('./components/MAJ/MAJ_addmirror.vue'), name: 'maj.addmirror'}
    ]
  }, ///////// REQUIRE AUTH

  { path: '/Web/:id(\\d+)', component: require('./components/Web/Web_group.vue'), beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "WEB")},
    children: [
      { path: '', component: require('./components/Web/Web.vue'), name: 'web'},
      { path: 'addvirtualhost', component: require('./components/Web/Web_addvirtualhost.vue'), name: 'web.addvirtualhost'},
      { path: 'editvirtualhost', component: require('./components/Web/Web_editvirtualhost.vue'), name: 'web.editvirtualhost'}
    ]
  }, ///////// REQUIRE AUTH

  { path: '/Proxy/:id(\\d+)', component: require('./components/Proxy\ Web/Proxy_group.vue'), beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "PROXY")},
    children: [
      { path: '', component: require('./components/Proxy\ Web/Proxy.vue'), name: 'proxy'},
      { path: 'addpool', component: require('./components/Proxy\ Web/Proxy_addpool.vue'), name: 'proxy.addpool'},
      { path: 'editpool', component: require('./components/Proxy\ Web/Proxy_editpool.vue'), name: 'proxy.editpool'},
      { path: 'addmember', component: require('./components/Proxy\ Web/Proxy_addmember.vue'), name: 'proxy.addmember'},
      { path: 'editmember', component: require('./components/Proxy\ Web/Proxy_editmember.vue'), name: 'proxy.editmember'}
    ]
  }, ///////// REQUIRE AUTH

  { path: '/Inventory/:id(\\d+)', component: require('./components/Inventory/Inventory.vue'), name: 'inventory', beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "INVENTORY")}}, ///////// REQUIRE AUTH
  { path: '/Ticketing/:id(\\d+)', component: require('./components/Ticketing/Ticketing.vue'), name: 'ticketing', beforeEnter: (to, from, next) => {redirect.checkServiceAndId(to, next, "TICKET")}}, ///////// REQUIRE AUTH

  { path: '/postman', component: require('./components/postman.vue'), name: 'postman'}, ///////// REQUIRE AUTH

  //{ path: '/logout', component: require('./components/Logout.vue'), name: 'log out'},

  //{ path: '*', redirect: '/' }
  ]
})

//Vue.http.options.root = process.env.NODE_ENV === "production" ? "/api" : "http://ihmtestfront.eloryal.fr/api"
Vue.http.options.root = "http://ihmtestcent.eloryal.fr/web/app.php"
/* IF PROD BUILD THEN HTTP.GET MUST POINTED TO THE CURRENT SERVER TO PROCESS HTTP REQUEST*/

/* eslint-disable no-new */
let vue = new Vue ({
  store,
  el: '#app',
  template: '<App/>',
  router,
  render: h => h(require('./App'))
})

export default vue
