<template>
    <div class="tableOfAdvUser">
        <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
        <div class="d-flex justify-content-center" v-if="isBusy"><fulfilling-bouncing-circle-spinner class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
        <div v-else>
          <h4 v-if="items.length === 0" class="font-weight-light text-center">Nejsou vloženy žádné inzeráty</h4>
          <b-table v-else striped hover :items="items" :fields="fields">
              <template slot="podrobnosti" slot-scope="row">
              <div class="row justify-content-md-center">
                  <b-button size="sm" variant="primary" v-on:click="details(row.item.id_adver)">Podrobnosti</b-button>
              </div>
              </template>
          </b-table>
        </div>
    </div>
</template>

<script>
import { FulfillingBouncingCircleSpinner } from 'epic-spinners';


// Komponenta pro výpis inzerátů uživatele
export default {
  name: 'table-of-adv-user',
  props:{
    /**
     * ID uživatele, jehož inzeáty se zobrazují
     * @default null
     * @type {Number}
     */
    id_user: {
      type: Number,
      default: null,
    },
    /**
     * V případě kliknutí na podrobnosti inzerátu, se tato informace posune ke komponentě advDetails, která zobrazí tlačítko zpět na podrobnosti uživatele 
     * @default false
     * @type {Boolean}
     */
      isAdmin: {
      type: Boolean,
      default: false,
    },
  },
  data () {
    return {
        alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: ''
        },
        fields: [
          {
            key: 'znacka',
            label: 'Značka',
            sortable: true
          },
          {
            key: 'model',
            label: 'Model',
            sortable: false
          },
          {
            key: 'popisek',
            label: 'Popisek',
            sortable: true,
          },
          {
            key: 'vyrobeno',
            label: 'Rok výroby',
            sortable: true,
          },
          {
            key: 'datum_vytvoreni',
            label: 'Vytvoření inzerátu',
            sortable: true,
          },
          {
          key: 'podrobnosti',
          label: '',
          sortable: false,
        }
        ],
        items: [],
        format: require('date-format'),
        isBusy: true,
    }
  },
  methods: {
      /**
       * @vuese
       * načitání z databáze inzeráty uživatele 
       */
      loadMyAdv(){
      this.isBusy = true;
      var id = (this.id_user === null) ? this.$root.user.id_user : this.id_user
      this.$http.get(process.env.API_URL+'v1/fl/adv/getMy/'+id, {
                headers: {
                'Authorization': 'Bearer '+this.$root.user.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.items = response.body;
                this.isBusy = false;
            }, function(response){
                if(response.status === 500){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = response.body.error.text;
                    this.alarm.type = 'danger';
                    this.isBusy = false;
                } else if(response.status === 401){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = response.body.message;
                    this.alarm.type = 'danger';
                    this.isBusy = false;
                }
            });
      },
      /**
       * @vuese
       * Přesměruje na detail inzerátu
       */
      details(id){
        var id_user = (this.id_user === null) ? this.$root.user.id_user : this.id_user
        if(this.isAdmin){
          this.$router.push({ name: 'advDetails', params: { id: id, id_user: id_user, isAdmin: true}});
        } else this.$router.push({ name: 'advDetails', params: { id: id, id_user: id_user}});
    }
  },
  created: function(){
      this.loadMyAdv();
  },
  components: {
    FulfillingBouncingCircleSpinner
  }
}
</script>

<style scoped>

</style>
