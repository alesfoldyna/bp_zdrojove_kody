// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import Resource from 'vue-resource'
import BootstrapVue from 'bootstrap-vue'
import navbarTop from '@/components/navbarTop';
import navbarLeft from '@/components/navbarLeft';
import ImageUploader from "vue-image-upload-resize";


Vue.use(ImageUploader);

Vue.use(Resource);
Vue.use(BootstrapVue);

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  router,
  data(){
    return{
      user: {},
    }
  },
  methods:{
    checkIsLogin(){
      if(Object.keys(this.user).length === 0 && this.user.constructor === Object) this.$router.push('/login');
      
      //check expiration of login
      this.$http.get(process.env.API_URL+'v1/open/server/time').then(function(response){
        if (response.body.time >= this.user.expires){
          this.user = {};
          this.$router.push({ name: 'Login', params: { isAlarm: true, AlarmText: 'Byly jste přihlášení dlouhou dobu. Přihlašte se prosím znovu.', alarmStyle: 'warning'} });
        }
      });
      
    },
    numberWithSpaces(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    },
    numberWithoutSpaces(x) {
      return x.replace(/ /g, '')*1;
    },
  },
  components: {
    navbarTop,
    navbarLeft
  },
  template: ` 
    <div id="app">
      <router-view></router-view>
    </div>
  `
}).$mount('#app')
