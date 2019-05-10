<template>
<div class="carBrandManagement">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
                <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <b-row align-h="between" align-v="center">
                    <b-col cols="auto"><h1 class="font-weight-light">Správa značky</h1></b-col>
                    <b-col cols="auto"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addCarBrand" aria-expanded="false" aria-controls="addCarBrand">Přidat</button></b-col>
                </b-row>
                <div class="collapse multi-collapse" id="addCarBrand">
                    <b-card bg-variant="light">
                        <b-form>
                            <b-row>
                                <b-col>
                                    <b-form-group label-cols-sm="2" label="Název značky:" label-align-sm="right" label-for="carBrandName">
                                        <b-form-input id="carBrandName" type="text" v-model="inputAddCarBrand.nazev" required/>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                            <b-button v-on:click="addCarBrand()" block variant="success">Přidat značku</b-button>
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
                <div class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner v-if="isBusy" class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                <div  v-if="!isBusy">
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
                    <template slot="upravit" slot-scope="row">
                    <div class="row justify-content-md-center">
                        <b-button size="sm" @click="row.toggleDetails" v-on:click="prepareVaule(row.item.nazev)" class="mr-2">Upravit</b-button>
                    </div>
                    </template>
                    <template slot="row-details" slot-scope="row">
                        <b-card bg-variant="light">
                        <b-form>
                            <b-row>
                                <b-col>
                                    <b-form-group label-cols-sm="2" label="Název značky:" label-align-sm="right" label-for="carBrandName">
                                        <b-form-input id="carBrandName" type="text" v-model="inputEditCarBrand.nazev" required/>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                            <b-button v-on:click="editCarBrand(row.item.id_znacka)" block variant="primary">Upravit značku</b-button>
                        </b-form>
                        </b-card>
                    </template>
                    <template slot="smazat" slot-scope="row">
                    <div class="row justify-content-md-center">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#potvrzeniSmazani" v-on:click="prepareValueForDelete(row.item.id_znacka, row.item.nazev)">smazat</button>
                        <div class="modal fade" id="potvrzeniSmazani" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Smazání značky</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center">
                                        Opravdu si přejete smazat značku: <b>{{dataForDelete.nazev}}</b>? 
                                    </p>
                                    <p class="text-center">
                                        Značka bude smázána poze v případě, kdy nebude použita v inzerátech. Je-li značka použita v inzerátech,
                                        musí se nejdříve odstranit veškeré inzeráty s touto značkou, aby bylo možno smazat značku.
                                    </p>
                                    <p class="text-danger text-center">
                                        <b>Při smazání značky se smažou i veškeré modely spojené s touto značkou!</b>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="deleteCarBrand(dataForDelete.id)">Smazat</button>
                                </div>
                                </div>
                            </div>
                        </div>
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

// Komponenta pro správu továrních značek automobilů
export default {
  name: 'carBrandManagement',
  data () {
    return {
         alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: ''
        },
        fields: [
          {
            key: 'id_znacka',
            label: 'ID značky',
            sortable: true
          },
          {
            key: 'nazev',
            label: 'Název',
            sortable: true
          },
          {
            key: 'upravit',
            label: '',
            sortable: false,
          },
          {
            key: 'smazat',
            label: '',
            sortable: false,
          }
        ],
        items: [],
        currentPage: 1,
        perPage: 10,
        totalRows: 0,
        pageOptions: [10, 20, 30],
        sortBy: 'nazev',
        sortDesc: false,
        filter: null,
        modalInfo: { title: '', content: '' },
        isBusy: true,
        inputEditCarBrand: {
            nazev: null,
        },
        inputAddCarBrand: {
            nazev: null,
        },
        dataForDelete: {
            id: null,
            nazev: null,
        }
    }
  },
  methods: {
      /**
       * @vuese
       * Připravuje hodnoty pro smazání značky. Jako položka tabulky se musí uložit.
       * @arg id idetifikátor znacky
       * @arg nazev název značky
       */
      prepareValueForDelete(id,nazev){
          this.dataForDelete.id = id; 
          this.dataForDelete.nazev = nazev;
      },
      /**
       * @vuese
       * Ukládá název značky pro úpravu. Aby se mohl zobrazit v modalním okně pro úpravu.
       */
      prepareVaule(nazev){
          this.inputEditCarBrand.nazev = nazev;
      },
      /**
       * @vuese
       * Požadavek na API pro přidání značky do databáze
       */
      addCarBrand(){
        let carBrandParam = {
              nazev: this.inputAddCarBrand.nazev,
        }

        let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }

          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.post(process.env.API_URL+'v1/admin/carBrand/add', carBrandParam, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Značka byla úspěšně přidána';
                this.alarm.type = 'success';

                this.inputAddCarBrand.nazev = null;
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
      },
      /**
       * @vuese
       * Požadavek na API pro úpravu názvu značky
       */
      editCarBrand(id){
        let carBrandParam = {
              nazev: this.inputEditCarBrand.nazev,
        }

        let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }

          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.put(process.env.API_URL+'v1/admin/carBrand/edit/'+id, carBrandParam, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Značka byla úspěšně upravena';
                this.alarm.type = 'success';
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
      },
      /**
       * @vuese
       * Požadavek na API pro smazání značky z databáze
       */
      deleteCarBrand(id){
            let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
            }

            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.delete(process.env.API_URL+'v1/admin/carBrand/delete/'+id, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Značka byla úspěšně smazána';
                    this.alarm.type = 'warning';
                    this.init();
                }, function(response){
                    if(response.status === 500){
                        this.alarm.isAlarm = true;
                        if(parseInt(response.body.error.code) === 23503){
                            this.alarm.AlarmText = 'Značka je použitá v inzerátech - NELZE SMAZAT!';
                        } else this.alarm.AlarmText = response.body.error.text;
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
      },
      /**
       * @vuese
       * Požadavek na API pro získání všech značek v databázi
       */
      init(){
          let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }
          this.$http.get(process.env.API_URL+'v1/open/znacka').then(function(response){
                this.items = response.body;
                this.totalRows = response.body.length;
                this.isBusy = false;
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
       * Upřesňuje počet položek v tabulce po filtraci
       */
      onFiltered(filteredItems) {
        this.totalRows = filteredItems.length
        this.currentPage = 1
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
div.carBrandManagement{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>