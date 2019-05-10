<template>
<div class="gearManagement">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
                <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <b-row align-h="between" align-v="center">
                    <b-col cols="auto"><h1 class="font-weight-light">Správa výbavy</h1></b-col>
                    <b-col cols="auto"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addGear" aria-expanded="false" aria-controls="addGear">Přidat</button></b-col>
                </b-row>
                <div class="collapse multi-collapse" id="addGear">
                    <b-card bg-variant="light">
                        <b-form>
                            <b-row>
                                <b-col>
                                    <b-form-group label-cols-sm="2" label="Název výbavy:" label-align-sm="right" label-for="gearName">
                                        <b-form-input id="gearName" type="text" v-model="inputAddGear.nazev" required/>
                                    </b-form-group>
                                </b-col>
                                <b-col>
                                    <label class="" for="selectUserType">Kategorie</label>
                                    <select class="custom-select" id="selectUserType" v-model="inputAddGear.kategorie" required>
                                        <option value="Technické">Technické</option>
                                        <option value="Bezpečnost">Bezpečnost</option>
                                        <option value="Asistent">Asistent</option>
                                        <option value="Pohodlí">Pohodlí</option>
                                    </select>
                                </b-col>
                            </b-row>
                            <b-button v-on:click="addGear()" block variant="success">Přidat výbavu</b-button>
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
                        <b-button size="sm" @click="row.toggleDetails" v-on:click="prepareVaule(row.item.nazev, row.item.kategorie)" class="mr-2">Upravit</b-button>
                    </div>
                    </template>
                    <template slot="row-details" slot-scope="row">
                        <b-card bg-variant="light">
                        <b-form>
                            <b-row>
                                <b-col>
                                    <b-form-group label-cols-sm="2" label="Název výbavy:" label-align-sm="right" label-for="gearName">
                                        <b-form-input id="gearName" type="text" v-model="inputEditGear.nazev" required/>
                                    </b-form-group>
                                </b-col>
                                <b-col>
                                    <label for="selectUserType">Kategorie</label>
                                    <select class="custom-select" id="selectUserType" v-model="inputEditGear.kategorie" required>
                                        <option value="Technické">Technické</option>
                                        <option value="Bezpečnost">Bezpečnost</option>
                                        <option value="Asistent">Asistent</option>
                                        <option value="Pohodlí">Pohodlí</option>
                                    </select>
                                </b-col>
                            </b-row>
                            <b-button v-on:click="editGear(row.item.id_vybava)" block variant="primary">Upravit výbavu</b-button>
                        </b-form>
                        </b-card>
                    </template>
                    <template slot="smazat" slot-scope="row">
                    <div class="row justify-content-md-center">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" v-on:click="prepareValueForDelete(row.item.id_vybava, row.item.nazev)" data-target="#potvrzeniSmazani">smazat</button>
                        <div class="modal fade" id="potvrzeniSmazani" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Smazání výbavy</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">Opravdu si přejete smazat výbavu: {{dataForDelete.nazev}}? Výbava bude smázána ze všech inzerátů a modelů, které ji mají přiřazenou.</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="deleteGear(dataForDelete.id)">Smazat</button>
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
// Komponenta pro správu výbavy
export default {
  name: 'gearManagement',
  data () {
    return {
         alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: ''
        },
        fields: [
          {
            key: 'id_vybava',
            label: 'ID výbavy',
            sortable: true
          },
          {
            key: 'nazev',
            label: 'Výbava',
            sortable: false
          },
          {
            key: 'kategorie',
            label: 'Kategorie',
            sortable: true,
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
        sortBy: 'kategorie',
        sortDesc: false,
        filter: null,
        modalInfo: { title: '', content: '' },
        isBusy: true,
        inputEditGear: {
            nazev: null,
            kategorie: null
        },
        inputAddGear: {
            nazev: null,
            kategorie: 'Technické'
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
         * Připravuje hodnoty pro smazání výbavy. Jako položka tabulky se musí uložit.
         * @arg id idetifikátor výbavy
         * @arg nazev název výbavy
         */
        prepareValueForDelete(id,nazev){
            this.dataForDelete.id = id; 
            this.dataForDelete.nazev = nazev;
        },
        /**
         * @vuese
         * Ukládá názevy výbavy a kategorii pro úpravu. Aby se mohly zobrazit v modalním okně pro úpravu.
         */
        prepareVaule(nazev, kategorie){
            this.inputEditGear.nazev = nazev;
            this.inputEditGear.kategorie = kategorie;
        },
        /** 
        * @vuese
        * Požadavek do API pro přidání výbavy 
        */
        addGear(){
            let gearParam = {
                nazev: this.inputAddGear.nazev,
                kategorie: this.inputAddGear.kategorie,
            }

            let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
            }

            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.post(process.env.API_URL+'v1/admin/gear/add', gearParam, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Výbava byla úspěšně přidána';
                    this.alarm.type = 'success';

                    this.inputAddGear.nazev = null;
                    this.inputAddGear.kategorie = 'Technické';
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
        * Požadavek do API pro úpravu výbavy
        * @arg id identifikátor výbavy
        */
        editGear(id){
            let gearParam = {
                nazev: this.inputEditGear.nazev,
                kategorie: this.inputEditGear.kategorie,
            }

            let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
            }

            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.put(process.env.API_URL+'v1/admin/gear/edit/'+id, gearParam, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Výbava byla úspěšně upravena';
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
        * Požadavek do API na smazání výbavy
        * @arg id identifikátor výbavy 
        */
        deleteGear(id){
                let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
                }

                this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                    this.$http.delete(process.env.API_URL+'v1/admin/gear/delete/'+id, {
                        headers: {
                        'Authorization': 'Bearer '+ response.body.token,
                        'Accept': 'application/json'
                        }
                    }).then(function(response){
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = 'Výbava byla úspěšně smazána';
                        this.alarm.type = 'warning';
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
        * Požadavek do API na získání všech výbav v databázi
        */
        init(){
            let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
            }
            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.get(process.env.API_URL+'v1/admin/gear', {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.items = response.body;
                    this.totalRows = response.body.length;
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
         * 
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
div.gearManagement{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>