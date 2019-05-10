<template>
<div class="editUsers">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
                <b-row align-h="between" align-v="center"> 
                    <b-col cols="auto"><h1 class="font-weight-light">Upravit uživatele</h1></b-col>
                    <b-col cols="auto"><router-link :to="{name: 'userDetail', params: {id_user: $route.params.id_user}}" class="btn btn-secondary">Zpět</router-link></b-col>
                </b-row>
                <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <div v-if="isBusy" class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                <form v-else v-on:submit.prevent="editUser">  
                    <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Typ uživatele</h5>
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-3">
                                    <div class="mt-2 mb-2" id="UserType">
                                        <b v-if="inputData.user_type === 'PROFI'">Profesionální prodejce</b>
                                        <b v-else-if="inputData.user_type === 'ADMIN'">Administrátor</b>
                                        <b v-else-if="inputData.user_type === 'NOTPROFI'">Soukromý prodejce</b>
                                    </div>
                                </div>
                                <div v-if="inputData.user_type === 'PROFI'" class="form-group col-md-3 mt-1">
                                    <label for="count_limit">Limit počtu inzerátů</label>
                                    <input type="number" class="form-control" id="count_limit" min="0" placeholder="Limit počtu inzerátů" v-model="inputData.count_limit" :required="inputData.user_type === 'PROFI'">
                                </div>
                            </div>
                            <h5 class="card-title">Osobní údaje</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="jmeno">Jméno</label>
                                <input type="text" class="form-control" id="jmeno" placeholder="Jméno" v-model="inputData.firstname" required>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="prijmeni">Příjmení</label>
                                <input type="text" class="form-control" id="prijmeni" placeholder="Příjmení" v-model="inputData.lastname" vrequired>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="adresa">Adresa</label>
                                <input type="text" class="form-control" id="adresa" placeholder="Ulice ČP/ČO" v-model="inputData.adress" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="mesto">Město</label>
                                <input type="text" class="form-control" id="mesto"  placeholder="Město" v-model="inputData.city" required>
                                </div>
                                <div class="form-group col-md-2">
                                <label for="psc">PSČ</label>
                                <input type="number" class="form-control" placeholder="PSČ" id="psc" min="10000" max="99999" v-model="inputData.psc" required>
                                </div>
                            </div>
                            <h5 class="card-title">Kontaktní údaje</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control" id="email" placeholder="E-mail" v-model="inputData.email" required>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="phone">Telefonní číslo</label>
                                <input type="number" class="form-control" id="phone" placeholder="telefoní číslo pouze ve formátu čísel bez mezer a předvolby" v-model="inputData.phone" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pravnickaOsoba" v-model="inputData.company.isCompany">
                                <label class="form-check-label" for="pravnickaOsoba">
                                    Uživatel zakládá účet na firmu?
                                </label>
                                </div>
                            </div>
                            <b-collapse id="company" :visible="inputData.company.isCompany">
                               <h5 class="card-title">Firemní údaje</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <label for="nazev">Název</label>
                                    <input type="text" class="form-control" id="nazev" placeholder="Název" v-model="inputData.company.name" :required="inputData.company.isCompany">
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="ic">IČ</label>
                                    <input type="number" class="form-control" id="ic" placeholder="IČ" v-model="inputData.company.ic" :required="inputData.company.isCompany">
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="dic">DIČ</label>
                                    <input type="text" class="form-control" id="dic" placeholder="DIČ" v-model="inputData.company.dic" :required="inputData.company.isCompany">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sidlo">Adresa sídla</label>
                                    <input type="text" class="form-control" id="sidlo" placeholder="Ulice ČP/ČO" v-model="inputData.company.adress" :required="inputData.company.isCompany">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <label for="po_mesto">Město</label>
                                    <input type="text" class="form-control" id="po_mesto"  placeholder="Město" v-model="inputData.company.city" :required="inputData.company.isCompany">
                                    </div>
                                    <div class="form-group col-md-2">
                                    <label for="po_psc">PSČ</label>
                                    <input type="number" class="form-control" placeholder="PSČ" id="po_psc" min="10000" max="99999" v-model="inputData.company.psc" :required="inputData.company.isCompany">
                                    </div>
                                </div>
                            </b-collapse>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-block btn-primary mt-3 mb-3">Upravit účet</button>
                </form>
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
// Komponenta pro úpravu profesionálního prodejce a administrátora. Neumožňuje měnit jeho typ.
export default {
  name: 'editUsers',
  data () {
    return {
        alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: ''
        },
        inputData: {
            user_type: null,
            count_limit: null,
            firstname: null,
            lastname: null,
            adress: null,
            city: null,
            psc: null,
            email: null,
            phone: null,
            company: {
                isCompany: false,
                ic: null,
                name: null,
                adress: null,
                city: null,
                psc: null,
                dic: null
            }
        },
        isBusy: true,
    }
  },
  methods: {
        /**
        * @vuese
        * Požadavek do API na načtení dat o uživateli
        */
        init(){
            this.isBusy = true;
            let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
            }
            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.get(process.env.API_URL+'v1/admin/user/' + this.$route.params.id_user, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){

                    this.inputData.user_type = response.body.user_type;
                    this.inputData.count_limit = (response.body.user_type === 'PROFI') ? response.body.count_limit : null;
                    this.inputData.firstname = response.body.jmeno;
                    this.inputData.lastname = response.body.prijmeni;
                    this.inputData.adress = response.body.adresa;
                    this.inputData.city = response.body.mesto;
                    this.inputData.psc = response.body.psc;
                    this.inputData.phone = response.body.telefon;
                    this.inputData.email = response.body.email;
                    this.inputData.company.isCompany = response.body.is_po;
                    
                    if(response.body.is_po){
                        this.inputData.company.ic = response.body.po_ic;
                        this.inputData.company.name = response.body.po_nazev;
                        this.inputData.company.adress = response.body.po_adresa;
                        this.inputData.company.city = response.body.po_mesto;
                        this.inputData.company.psc = response.body.po_psc;
                        this.inputData.company.dic = response.body.po_dic;
                    }  
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
                    } else {
                        this.alarm.isAlarm = true;
                        this.alarm.AlarmText = 'Unknow error';
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
        * Požadavek do API na úpravu dat uživatele
        */
        editUser(){
                let userParam = {
                    email: this.inputData.email,
                    user_type: this.inputData.user_type,
                    jmeno: this.inputData.firstname,
                    prijmeni: this.inputData.lastname,
                    adresa: this.inputData.adress,
                    mesto: this.inputData.city,
                    psc: parseInt(this.inputData.psc),
                    telefon: parseInt(this.inputData.phone),
                    is_po: this.inputData.company.isCompany ? 1 : 0,
                }

                if(this.inputData.company.isCompany){
                    let companyData = {
                        po_ic: this.inputData.company.ic,
                        po_nazev: this.inputData.company.name,
                        po_adresa: this.inputData.company.adress,
                        po_mesto: this.inputData.company.city,
                        po_psc: parseInt(this.inputData.company.psc),
                        po_dic: this.inputData.company.dic,
                    }

                    userParam  = Object.assign(companyData, userParam);
                }

                if(this.inputData.user_type === 'PROFI'){
                    let puData = {
                        count_limit: parseInt(this.inputData.count_limit)
                    }

                    userParam  = Object.assign(puData, userParam);
                }
                
                let param = {
                    id_user: this.$root.user.id_user,
                    pass: this.$root.user.pass
                }

                this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                    this.$http.put(process.env.API_URL+'v1/admin/user/edit/'+this.$route.params.id_user, userParam, {
                        headers: {
                        'Authorization': 'Bearer '+ response.body.token,
                        'Accept': 'application/json'
                        }
                    }).then(function(response){
                        this.$router.push({ name: 'userDetail', params: { id_user: this.$route.params.id_user,  isAlarm: true, AlarmText: 'Uživatel byl úspěšně upraven', alarmStyle: 'success'} });
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
    FulfillingBouncingCircleSpinner
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.editUsers{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>
