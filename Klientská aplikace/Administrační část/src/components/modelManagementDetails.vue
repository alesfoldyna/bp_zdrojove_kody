<template>
    <div class="modelManagementDetails">
        <navbarTop></navbarTop>
        <div class="container-fluid h-100">
            <div class="row h-100">
                <navbarLeft></navbarLeft>
                <main class="col">
                    <div class="container mt-3">
                        <h1 class="font-weight-light">Detaily modelu</h1>
                        <hr>
                        <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                        <router-link to="/modelManagementList" class="btn btn-secondary mb-3 float-left">Zpět na přehled</router-link>
                        <div class="d-flex flex-row-reverse mb-3">
                            <button type="button" class="btn btn-danger ml-3" data-toggle="modal" data-target="#potvrzeniSmazani">smazat</button>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#editModel" aria-expanded="false" aria-controls="editModel">Upravit</button>
                        </div>
                        <div class="collapse multi-collapse" id="editModel">
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
                                    <b-button v-on:click="editModel($route.params.id_model)" block variant="success">Upravit model</b-button>
                                </b-form>
                            </b-card>
                        </div>
                        <div class="modal fade" id="potvrzeniSmazani" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Smazání Inzerátu</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center">
                                        Opravdu si přejete smazat model: <b>{{modelDetail.nazev}}</b>? 
                                    </p>
                                    <p class="text-center">
                                        Model bude smázán poze v případě, kdy nebude používán v inzerátech. Je-li model použitý v inzerátech,
                                        musí se nejdříve odstranit veškeré inzeráty s tímto modelem, aby bylo možno smazat model.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="deleteModel($route.params.id_model)">Smazat</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div  v-if="isBusy" class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                        <b-card v-else bg-variant="light">
                            <h4 class="font-weight-light">{{modelDetail.nazev_znacky}} <b>{{modelDetail.nazev}}</b></h4>
                            <hr class="card-separator">
                            <b-row class="mb-3">
                                <b-col class="font-weight-bold"  col lg="2">Vyrabí se od:</b-col>
                                <b-col>{{modelDetail.vyrobeno_od}}</b-col>
                            </b-row>
                            <b-row class="mb-3">
                                <b-col class="font-weight-bold"  col lg="2">Vyrabí se do:</b-col>
                                <b-col v-if="modelDetail.vyrobeno_do !== null">{{modelDetail.vyrobeno_do}}</b-col>
                                <b-col v-else class="text-success">Nyní</b-col>
                            </b-row>
                            <b-row>
                                <b-col class="font-weight-bold"  col lg="2">Možnosti výbavy:</b-col>
                                <b-col v-if="modelsVybava !== []">{{modelsVybava.toString().replace(/,/g, ', ')}}</b-col>
                            </b-row>
                        </b-card>
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
// Komponenta pro zobrazení detailů modelu
export default {
  name: 'modelManagementDetails',
  data () {
    return {
        alarm: {
            isAlarm: false,
            type: 'danger',
            AlarmText: ''
        },
        modelDetail: {},
        modelsVybava: [],
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
         * Požadavek na API pro získání informací o modelu
         */
        init(){
                let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
                }
                this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                    this.$http.get(process.env.API_URL+'v1/admin/model/'+this.$route.params.id_model, {
                        headers: {
                        'Authorization': 'Bearer '+ response.body.token,
                        'Accept': 'application/json'
                        }
                    }).then(function(response){
                        this.modelDetail = response.body;
                        if(response.body.id_vybava !== null){
                            this.$http.get(process.env.API_URL+'v1/open/vybava/'+response.body.id_vybava.replace(/{|}/g,'')).then(function(response){
                                for(var i = 0; i < response.body.length; i++) this.modelsVybava.push(response.body[i].nazev);
                                this.initEditForm();
                                this.isBusy = false;
                            }, function(response){
                                this.alarm.isAlarm = true;
                                this.alarm.AlarmText = response.body.message;
                                this.alarm.type = 'danger';
                                this.isBusy = false;
                            });
                        } else {
                            this.isBusy = false;
                            this.initEditForm();
                        }
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
                this.loadZnacka();
                this.loadVybava();
                this.loadYears();
                this.loadKategorieVybav();
        },
        /**
         * @vuese
         * Požadavek do API na úpravu modelu
         * @arg id identifikátor modelu
         */
        editModel(id){
             if(this.inputs.id_znacka === null || this.inputs.nazev === null || this.inputs.vyrobeno_od === null ){
                this.alarm.AlarmText = '"Značka", "název" a "Výrábí se od" musí být zadány';
                this.alarm.type = 'danger';
                this.alarm.isAlarm = true;
                return;
            }
            let editModelParam = {
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
                this.$http.put(process.env.API_URL+'v1/admin/models/edit/'+id, editModelParam, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Model byla úspěšně upraven';
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
         * Požadavek do API pro smazání modelu
         * @arg id identifikátor modelu
         */
        deleteModel(id){
            let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
            }

            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.delete(process.env.API_URL+'v1/admin/models/delete/'+id, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.$router.push({ name: 'modelManagementList', params: { isAlarm: true, AlarmText: 'Model byl úspěšně vymazán', alarmStyle: 'warning'} });
                }, function(response){
                    if(response.status === 500){
                        this.alarm.isAlarm = true;
                        if(parseInt(response.body.error.code) === 23503){
                            this.alarm.AlarmText = 'Model je použitý v inzerátech - NELZE SMAZAT!';
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
         * Načtení dat do formuláře pro úpravu
         */
        initEditForm(){
            this.inputs.id_znacka  = this.modelDetail.id_znacka;
            this.inputs.nazev = this.modelDetail.nazev;
            this.inputs.vyrobeno_od = this.modelDetail.vyrobeno_od;
            this.inputs.vyrobeno_do = this.modelDetail.vyrobeno_do;
            this.inputs.id_vybava = (this.modelDetail.id_vybava !== null) ? this.modelDetail.id_vybava.replace('{','').replace('}','').split(',') : [];
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
  },
  created: function(){
        this.$root.checkIsLogin();
        this.init();
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
    div.modelManagementDetails{
         height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
    }
    hr.card-separator {
    border: 0;
    height: 1px;
    background: #333;
    background-image: linear-gradient(to right, #ccc, #333, #ccc);
}
</style>
