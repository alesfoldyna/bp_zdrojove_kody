// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import router from './router'
import Resource from 'vue-resource'
import VueLodash from 'vue-lodash'
import BootstrapVue from 'bootstrap-vue'
import VuePaginateAl from 'vue-paginate-al'
import navbar from './components/navbar.vue'


const options = { name: 'lodash' }; // customize the way you want to call it

Vue.use(VueLodash, options); // options is optional
Vue.use(Resource);
Vue.use(BootstrapVue);
Vue.component('vue-paginate-al', VuePaginateAl)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  router,
  data(){
    return{
      search: {
        znacka: null,
        model: null,
        vybava: [],
        cenaOd: null,
        cenaDo: null,
        vyrobenoOd: null,
        vyrobenoDo: null,
        najetoOd: null,
        najetoDo:null
      },
      resultOfSearch: [],
      indexOfSearchPage: 0,
      user: {
        email: null,
        token: null,
        firstName: null,
        lastName: null,
      },
      isBusy: true,
    }
  },
  methods:{
    /**
     * @vuese
     * Upravuje číslo tak, aby mělo mezi třemi čísly mezeru. Mezery přidává od konce.
     * @arg x číslo na rozdělení 
     */
    numberWithSpaces(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    },
    /**
     * @vuese
     * Maže z číseného stringu mezeru po každé třetí čáslovce a vrací int.
     * @arg x číslo ve stringu obsahující mezery
     */
    numberWithoutSpaces(x) {
      return x.replace(/ /g, '')*1;
    },
    /**
     * @vuese
     * Požadavek do API na vyhledání inzerátů
     */
    loadFindList(){
      this.isBusy = true;
      let prepareParam = {
        id_znacka: this.$root.search.znacka,
        id_model: this.$root.search.model,
        id_vybava: this.$root.search.vybava,
        cena_od: this.$root.search.cenaOd,
        cena_do:  this.$root.search.cenaDo,
        vyrobeno_od: this.$root.search.vyrobenoOd,
        vyrobeno_do: this.$root.search.vyrobenoDo,
        najeto_od:  this.$root.search.najetoOd,
        najeto_do:  this.$root.search.najetoDo
      };

      (prepareParam.id_vybava.length !== 0) ? prepareParam.id_vybava = prepareParam.id_vybava.toString() : prepareParam.id_vybava = null;
      if (prepareParam.cena_od !== null) prepareParam.cena_od = this.$root.numberWithoutSpaces(prepareParam.cena_od);
      if (prepareParam.cena_do !== null) prepareParam.cena_do = this.$root.numberWithoutSpaces(prepareParam.cena_do);
      if (prepareParam.najeto_od !== null) prepareParam.najeto_od = this.$root.numberWithoutSpaces(prepareParam.najeto_od);
      if (prepareParam.najeto_do !== null) prepareParam.najeto_do = this.$root.numberWithoutSpaces(prepareParam.najeto_do);
      //{'id_znacka': 1, 'id_model': 3, 'id_vybava': null, 'cena_od': null}
      this.$http.post(process.env.API_URL+'v1/open/adv/content', prepareParam)
      .then(function(response){
        this.resultOfSearch = this.chunkArray(response.body, 10);
        this.isBusy = false;
      });
    },
    /**
     * @vuese
     * Rozděluje pole výsledků po zvoleném množství
     * @arg arr pole, které má být rozděleno
     * @arg size zvolené množství prvků na jedno pole
     */
    chunkArray(arr, size){
      var results = [];
      while (arr.length) {
          results.push(arr.splice(0, size));
      }    
      return results;
    }
  },
  components: {
    navbar
  },
  template: ` 
    <div id="app">
      <navbar></navbar>
      <router-view></router-view>
    </div>

  `
}).$mount('#app')
