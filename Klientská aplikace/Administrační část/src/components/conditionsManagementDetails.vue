<template>
    <div class="conditionsManagementDetails">
        <navbarTop></navbarTop>
        <div class="container-fluid h-100">
            <div class="row h-100">
                <navbarLeft></navbarLeft>
                <main class="col">
                    <div class="container mt-3">
                        <h1 class="font-weight-light">Detaily všeobecné podmínky</h1>
                        <hr>
                        <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                        <router-link to="/conditionsManagementList" class="btn btn-secondary mb-3 float-left">Zpět na přehled</router-link>
                        <div class="d-flex flex-row-reverse mb-3">
                            <button type="button" class="btn btn-danger ml-3" data-toggle="modal" data-target="#potvrzeniSmazani">smazat</button>
                        </div>
                        <div class="modal fade" id="potvrzeniSmazani" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Smazání všeobecné podmínky</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center">
                                        Opravdu si přejete smazat tuto všeobecnou podmínku? 
                                    </p>
                                    <p class="text-center">
                                        Podmínka bude smázána pouze v případě, kdy všichni soukromý prodejci budo mít přijatou novou všeobecnou podmínku.
                                        V opačném případě k smazaní nedojde.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="deleteConditions($route.params.id_podminka)">Smazat</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div  v-if="isBusy" class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                        <b-card v-else bg-variant="light">
                            <b-row align-h="between" align-v="center">
                                <b-col cols="auto"><h4 class="font-weight-light">Všeobecná podmínka: <b>{{condition.id_podminka}}</b></h4></b-col>
                                <b-col cols="auto"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#editConditional" aria-expanded="false" aria-controls="editConditional">Upravit</button></b-col>
                            </b-row>
                            <div class="collapse multi-collapse mt-3" id="editConditional">
                                <b-card bg-variant="light" header="Upravit podmínku">
                                    <b-form>
                                        <b-row>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Platna do:" label-align-sm="right" label-for="platnost_do">
                                                    <b-form-input id="platnost_do" type="date" v-model="inputs.platnost_do"/>
                                                    <small id="popisek" class="form-text text-muted" >Pro dobu neurčitou nechte nevyplněné</small>
                                                </b-form-group>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Platna:" label-align-sm="right" label-for="platna">
                                                    <b-form-checkbox id="platna" v-model="inputs.platna">Zaškrtněte, pokud podmínka je již platná</b-form-checkbox>
                                                </b-form-group>
                                            </b-col>
                                        </b-row>
                                        <b-button v-on:click="editCondition($route.params.id_podminka)" block variant="success">Upravit podmínku</b-button>
                                    </b-form>
                                </b-card>
                            </div>
                            <hr class="card-separator">
                            <b-row class="mb-3">
                                <b-col class="font-weight-bold"  col lg="2">Platná od:</b-col>
                                <b-col>{{condition.platnost_od}}</b-col>
                            </b-row>
                            <b-row class="mb-3">
                                <b-col class="font-weight-bold"  col lg="2">Platná do:</b-col>
                                <b-col v-if="condition.platnost_do === null" class="text-success">Na dobu neurčitou</b-col>
                                <b-col v-else>{{condition.platnost_do}}</b-col>
                            </b-row>
                            <b-row>
                                <b-col class="font-weight-bold"  col lg="2">Platna:</b-col>
                                <b-col v-if="condition.platna" class="text-success">ANO</b-col>
                                <b-col v-else class="text-danger">NE</b-col>
                            </b-row>
                            <h4 class="text-center font-weight-light mt-3">Znění podmínky</h4>
                            <iframe :srcdoc="condition.zneni" scrolling class="w-100"></iframe>
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
import VueFriendlyIframe from 'vue-friendly-iframe';
// Komponenta pro zobrazení detailu všeobecné podmínky.
export default {
  name: 'conditionsManagementDetails',
  data () {
    return {
        alarm: {
            isAlarm: false,
            type: 'danger',
            AlarmText: ''
        },
        condition: {},
        inputs: {
            platnost_do: null,
            platna: true,
        },
        htmlZneni: null,
        isBusy: true,
    }
  },
  methods: {
        /**
         * @vuese
         * Požadavek do API na získání informácí se zněním všeobecné podmínky
         */
        init(){
            let param = {
            id_user: this.$root.user.id_user,
            pass: this.$root.user.pass
            }
            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.get(process.env.API_URL+'v1/admin/condition/'+this.$route.params.id_podminka, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.condition = response.body;
                    this.inputs.platnost_do = response.body.platnost_do;
                    this.inputs.platna = response.body.platna;
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
         * Požadavek do API na smazání všeobecné podmínky
         */
        deleteConditions(id){
            let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
                }
            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.delete(process.env.API_URL+'v1/admin/conditions/delete/'+id, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.$router.push({ name: 'conditionsManagementList', params: { isAlarm: true, AlarmText: 'Všeobecná podmínka byla úspěšně vymazána', alarmStyle: 'warning'} });
                }, function(response){
                    if(response.status === 500){
                        this.alarm.isAlarm = true;
                        if(parseInt(response.body.error.code) === 23503){
                            this.alarm.AlarmText = 'Všeobecnou podmínku má některý z uživatelů stále aktivní. NELZE SMAZAT!';
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
         * Požadavek do API na úpravu všeobecné podmínky
         */
        editCondition(id){
            let editConditionParam = {
                    platnost_do: this.inputs.platnost_do,
                    platna: this.inputs.platna ? 1 : 0,
                }
                let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
                }
                console.log(editConditionParam)
                this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                    this.$http.put(process.env.API_URL+'v1/admin/conditions/edit/'+id, editConditionParam, {
                        headers: {
                        'Authorization': 'Bearer '+ response.body.token,
                        'Accept': 'application/json'
                        }
                    }).then(function(response){
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = 'Podmínka byla úspěšně upravena';
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
        }
  },
  created: function(){
    this.$root.checkIsLogin();
    this.init();
  },
  components: {
    navbarTop,
    navbarLeft,
    FulfillingBouncingCircleSpinner,
    VueFriendlyIframe
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.conditionsManagementDetails{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
div.textVP {
    min-height: 300px; 
    max-height: 500px;
    width: 100%;
    overflow: scroll;
    background-color: white;
}
iframe {
margin-top: 20px;
margin-bottom: 30px;
border: none;
background-color: white;
height: 600px;
} 
</style>
