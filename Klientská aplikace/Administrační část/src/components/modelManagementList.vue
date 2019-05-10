<template>
<div class="modelManagementList">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
                <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <b-row align-h="between" align-v="center">
                    <b-col cols="auto"><h1 class="font-weight-light">Správa modelu</h1></b-col>
                    <b-col cols="auto"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addModel" aria-expanded="false" aria-controls="addModel">Přidat</button></b-col>
                </b-row>
                <div class="collapse multi-collapse" id="addModel">
                    <b-card bg-variant="light" header="Přidaní modelu">
                        <b-form>
                            <b-row>
                                <b-col>
                                    <label for="selectZnacka">Značka</label>
                                    <select class="custom-select" id="selectZnacka" v-model="inputs.id_znacka" required>
                                        <option :value="null">Nevybráno</option>
                                        <option v-for="znacka in select.znacka" :key="znacka.id_znacka" v-bind:value="znacka.id_znacka">{{znacka.nazev}}</option>
                                    </select>
                                </b-col>
                                 <b-col>
                                    <b-form-group label-cols-sm="2" label="Název modelu:" label-align-sm="right" label-for="modelName">
                                        <b-form-input id="modelName" type="text" v-model="inputs.nazev" required/>
                                    </b-form-group>
                                    </b-col>
                                <b-col>
                                    <label for="selectZnacka">Výrábí se od:</label>
                                    <select class="custom-select" id="selectZnacka" v-model="inputs.vyrobeno_od" required>
                                        <option :value="null">Nevybráno</option>
                                        <option v-for="od in select.rok_od.range" :key="od" v-bind:value="od">{{od}}</option>
                                    </select>
                                </b-col>
                                <b-col>
                                    <label for="selectZnacka">Výrábí se do:</label>
                                    <select class="custom-select" id="selectZnacka" v-model="inputs.vyrobeno_do" required>
                                        <option :value="null">Nevybráno</option>
                                        <option v-for="rok_do in select.rok_od.range" :key="rok_do" v-bind:value="rok_do">{{rok_do}}</option>
                                    </select>
                                    <small id="popisek" class="form-text text-muted" >Vyrabí-li se model dosud, nechte nevybrané!</small>
                                </b-col>
                            </b-row>
                            <b-row class="mt-3 mb-3">
                                <b-col v-for="kategorieVybav in select.kategorie" v-bind:key="kategorieVybav.kategorie">
                                    <h4 class="font-weight-light">{{kategorieVybav.kategorie}}</h4>
                                    <div v-for="vyb in zobrazeniVybavy(kategorieVybav.kategorie)" v-bind:key="vyb.id_vybava" class="custom-control custom-checkbox mr2" :id="kategorieVybav.kategorie">
                                        <input class="custom-control-input" type="checkbox" v-bind:value="vyb.id_vybava" :id="vyb.id_vybava" v-model="inputs.id_vybava">
                                        <label class="custom-control-label" :for="vyb.id_vybava">{{vyb.nazev}}</label>
                                    </div>
                                </b-col>
                            </b-row>
                            <b-button v-on:click="addModel()" block variant="success">Přidat model</b-button>
                        </b-form>
                    </b-card>
                </div>
                <b-row>
                    <b-col md="6" class="my-1">
                        <b-form-group label-cols-sm="3" label="Filtr" class="mb-0">
                        <b-input-group>
                            <b-form-input v-model="filter" placeholder="Vyhledat" />
                            <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">Vyčistit</b-button>
                            </b-input-group-append>
                        </b-input-group>
                        </b-form-group>
                    </b-col>

                    <b-col md="6" class="my-1">
                        <b-form-group label-cols-sm="3" label="Seřadit" class="mb-0">
                        <b-input-group>
                            <b-form-select v-model="sortBy" :options="sortOptions">
                            <option slot="first" :value="null">-- none --</option>
                            </b-form-select>
                            <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
                            <option :value="false">Vzestupně</option> <option :value="true">Sestupně</option>
                            </b-form-select>
                        </b-input-group>
                        </b-form-group>
                    </b-col>

                    

                    <b-col md="6" class="my-1">
                        <b-form-group label-cols-sm="3" label="Na stránku" class="mb-0">
                        <b-form-select :options="pageOptions" v-model="perPage" />
                        </b-form-group>
                    </b-col>
                </b-row>
                <div  v-if="isBusy" class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                <div  v-else>
                <b-table
                    show-empty
                    stacked="md"
                    :items="items"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    @filtered="onFiltered"
                    >
                    <template slot="podrobnosti" slot-scope="row">
                    <div class="row justify-content-md-center">
                        <b-button size="sm" variant="primary" v-on:click="details(row.item.id_model)">Podrobnosti</b-button>
                    </div>
                    </template>

                </b-table>

                <b-row class="justify-content-md-center">
                    <b-col md="auto" class="my-1">
                        <b-pagination
                        :total-rows="totalRows"
                        :per-page="perPage"
                        v-model="currentPage"
                        class="my-0"
                        />
                    </b-col>
                </b-row>
                </div>
            </div>
          </main>
        </div>
      </div>
  </div>
</template>

<script>
import navbarTop from '@/components/navbarTop';
import navbarLeft from '@/components/navbarLeft';
import { FulfillingBouncingCircleSpinner } from 'epic-spinners';
// Komponenta pro zobrazení seznamu všech modelů v databázi
export default {
  name: 'modelManagementList',
  data () {
    return {
         alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: ''
        },
        fields: [
          {
            key: 'id_model',
            label: 'ID Modelu',
            sortable: true
          },
          {
            key: 'nazev_znacky',
            label: 'Značka',
            sortable: true
          },
          {
            key: 'nazev',
            label: 'Model',
            sortable: true,
          },
          {
            key: 'podrobnosti',
            label: '',
            sortable: false,
          },
        ],
        items: [],
        currentPage: 1,
        perPage: 10,
        totalRows: 0,
        pageOptions: [10, 20, 30],
        sortBy: 'nazev_znacky',
        sortDesc: false,
        filter: null,
        modalInfo: { title: '', content: '' },
        isBusy: true,
        select: {
            znacka: [],
            rok_od: {
                possibleFrom: null,
                possibleTo: null,
                range: []
            },
            rok_do: {
                possibleFrom: null,
                possibleTo: null,
                range: []
            },
            vybava: [],
            kategorie: [],
        },
        inputs: {
            id_znacka: null,
            nazev: null,
            vyrobeno_od: null,
            vyrobeno_do: null,
            id_vybava: [],
        }
    }
  },
  methods: {
      /**
       * @vuese
       * Požadavek do API pro získání všech modelů v databázi
       */
      init(){
          let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }
          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.get(process.env.API_URL+'v1/admin/models', {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.items = response.body;
                this.totalRows = response.body.length;
                this.loadZnacka();
                this.loadVybava();
                this.loadYears();
                this.loadKategorieVybav();
                this.isBusy = false;
            }, function(response){
                if(response.status === 500){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = response.body.error.text;
                    this.alarm.type = 'danger';
                } else if(response.status === 401){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = response.body.message;
                    this.alarm.type = 'danger';
                }
                this.isBusy = false;
            });
          },function(response){
              if(response.status === 500){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = response.body.error.text;
                    this.alarm.type = 'danger';
                } else if(response.status === 401){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = response.body.message;
                    this.alarm.type = 'danger';
                } else {
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Unknow error';
                    this.alarm.type = 'danger';
                }
                this.isBusy = false;
          });
      },
      /**
       * @vuese
       * Přesměrování na detail modelu
       * @arg id identifikátor modelu
       */
      details(id){
          this.$router.push({ name: 'modelManagementDetails', params: { id_model: id}});
      },
      /**
      * @vuese
      * Upřesňuje počet položek v tabulce po filtraci
      */
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      /**
        * @vuese
        * Požadavek do API na získání značek z databáze
        */
      loadZnacka(){
        this.$http.get(process.env.API_URL+'v1/open/znacka')
        .then(function(response){
            this.select.znacka = response.body;
        });
      },
      /**
        * @vuese
        * Požadavek do API na získání výbav z databáze
        */
      loadVybava(){
            this.$http.get(process.env.API_URL+'v1/open/vybava')
            .then(function(response){
                this.select.vybava = response.body;
            });
      },
      /**
        * @vuese
        * Požadavek do API na získání modelových let výroby
        */
      loadYears(){
            this.select.rok_od.possibleFrom = 1950;
            this.select.rok_od.possibleTo = (new Date()).getFullYear();
            this.select.rok_od.range = [...Array(this.select.rok_od.possibleTo- this.select.rok_od.possibleFrom+1).keys()].map(i => i + this.select.rok_od.possibleFrom).reverse();

            this.select.rok_do.possibleFrom = 1950;
            this.select.rok_do.possibleTo = (new Date()).getFullYear();
            this.select.rok_do.range = [...Array(this.select.rok_do.possibleTo- this.select.rok_do.possibleFrom+1).keys()].map(i => i + this.select.rok_do.possibleFrom).reverse();
      },
      /**
        * @vuese
        * Požadavek do API na získání kategorií výbav
        */
      loadKategorieVybav(){
        this.$http.get(process.env.API_URL+'v1/open/vybava/info/kategorie')
        .then(function(response){
            this.select.kategorie = response.body;
        });
      },
      /**
        * @vuese
        * Filtrační metoda, která rozděluje výbavu do kategoriií tak, aby byly vypsány ve sloupcích podle kategorie
        * @arg kategorie je string s názvem kategorie, která se zrovna vypisuje
        */
      zobrazeniVybavy(kategorie){
        var result = [];
        for(var i = 0; i<this.select.vybava.length; i++){
          if(this.select.vybava[i].kategorie === kategorie) result.push(this.select.vybava[i]);
        }
        return result;
      },
      /**
        * @vuese
        * Nuluje data ve formuláři na přidání modelu
        */
      nullAddForm(){
            this.inputs.id_znacka  = null;
            this.inputs.nazev = null;
            this.inputs.vyrobeno_od = null;
            this.inputs.vyrobeno_do = null;
            this.inputs.id_vybava = [];
      },
      /**
        * @vuese
        * Požadavek do API na přidání modelu
        */
      addModel(){
            if(this.inputs.id_znacka === null || this.inputs.nazev === null || this.inputs.vyrobeno_od === null ){
                this.alarm.AlarmText = '"Značka", "název" a "Výrábí se od" musí být zadány';
                this.alarm.type = 'danger';
                this.alarm.isAlarm = true;
                return;
            }
            let addModelParam = {
                id_znacka: this.inputs.id_znacka,
                nazev: this.inputs.nazev,
                vyrobeno_od: this.inputs.vyrobeno_od,
                vyrobeno_do: this.inputs.vyrobeno_do,
                id_vybava: (this.inputs.id_vybava.length !== 0) ? this.inputs.id_vybava.toString() : null
            }
            let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
            }
            
            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.post(process.env.API_URL+'v1/admin/models/add', addModelParam, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Model byla úspěšně přidán';
                    this.alarm.type = 'success';

                    this.nullAddForm();
                    this.init();
                }, function(response){
                    if(response.status === 500){
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = response.body.error.text;
                        this.alarm.type = 'danger';
                    } else if(response.status === 401){
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = response.body.message;
                        this.alarm.type = 'danger';
                    } else {
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = 'Unknow error';
                        this.alarm.type = 'danger';
                    }
                });
            },function(response){
                if(response.status === 500){
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = response.body.error.text;
                        this.alarm.type = 'danger';
                    } else if(response.status === 401){
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = response.body.message;
                        this.alarm.type = 'danger';
                    } else {
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = 'Unknow error';
                        this.alarm.type = 'danger';
                    }
            });

      }
  },
  computed: {
      /**
       * @vuese
       * Řadí výsledky podle zadaných kritérií
       */
      sortOptions() {
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      }
    },
  created: function(){
    this.$root.checkIsLogin();
    this.init();
    if(this.$route.params.isAlarm){
        this.alarm.AlarmText = this.$route.params.AlarmText;
        this.alarm.type = this.$route.params.alarmStyle;
        this.alarm.isAlarm = this.$route.params.isAlarm;
    }
  },
  components: {
    navbarTop,
    navbarLeft,
    FulfillingBouncingCircleSpinner
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.modelManagementList{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>