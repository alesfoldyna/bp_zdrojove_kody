<template>
<div class="AdminHome">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
              <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
              <div class="row justify-content-center">
                <div class="col-4">
                  <div class="card bg-light mt-3" style="height: 300px">
                    <div class="card-header" style="height: 63px">
                      <div class="row justify-content-between"><div class="col-auto mr-auto mt-2">Osobní údaje</div><div class="col-auto" v-show="$root.user.user_type === 'NOTPROFI'"><router-link to="editNpUser" class="btn btn-primary">Upravit</router-link></div></div>
                    </div>
                    <div class="card-body">
                      <h4>{{data.firstname}} {{data.lastname}}</h4>
                      <div class="font-weight-light">{{data.adress}}</div>
                      <div class="font-weight-light">{{data.city}}, {{data.psc}}</div>
                      <hr>
                      <div class="font-weight-light">{{data.email}}</div>
                      <div class="font-weight-light">{{data.phone}}</div>
                    </div>
                  </div>
                </div>
                <div class="col-4" v-show="data.company.isCompany">
                  <div class="card bg-light mt-3" style="height: 300px">
                    <div class="card-header pt-3" style="height: 63px">Firemní údaje</div>
                    <div class="card-body">
                      <h4>{{data.company.name}}</h4>
                      <h6 class="font-weight-light">IČ {{data.company.ic}}, DIČ {{data.company.dic}}</h6>
                      <div class="font-weight-light">{{data.company.adress}}</div>
                      <div class="font-weight-light">{{data.company.city}}, {{data.company.psc}}</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-4">
                  <div class="card text-center bg-light mt-3">
                    <div class="card-header">Změna hesla</div>
                    <div class="card-body">
                      <button v-on:click="changePass" class="btn btn-primary">Změnit</button>
                    </div>
                  </div>
                </div>

                <div v-show="$root.user.user_type === 'PROFI'" class="col-4">
                  <div class="card text-center bg-light mt-3">
                    <div class="card-header">Limit inzerátů</div>
                    <div class="card-body">
                      <b-row class="text-left">
                          <b-col cols="8">Počet povolených inzerátů:</b-col>
                          <b-col cols="4">{{$root.user.count_limit}}</b-col>
                      </b-row>
                      <b-row class="text-left">
                          <b-col cols="8">Počet vložených inzerátů:</b-col>
                          <b-col cols="4">{{pocetVlozenychInzeratu}}</b-col>
                      </b-row>
                      <b-row class="text-left">
                          <b-col cols="8">Počet volných vložení:</b-col>
                          <b-col cols="4">{{parseInt($root.user.count_limit)-parseInt(pocetVlozenychInzeratu)}}</b-col>
                      </b-row>
                    </div>
                  </div>
                </div>

                <div class="col-4">
                  <div class="card text-center bg-light mt-3">
                    <div class="card-header">Odkazy</div>
                    <div class="card-body">
                      <ul class="navbar-nav">
                        <li><router-link to="advList" class="nav-item">Přehled mých inzerátů</router-link></li>
                        <li><router-link to="addAdvertising" class="nav-item">Přidat inzerát</router-link></li>
                        <li v-if="$root.user.user_type === 'ADMIN'"><router-link to="advForCheck" class="nav-item">Inzeráty ke kontrole</router-link></li>
                      </ul>
                      <hr class="link-separator" v-if="($root.user.user_type === 'ADMIN' || $root.user.user_type === 'PROFI')">
                      <ul class="navbar-nav" v-if="$root.user.user_type === 'ADMIN'">
                        <li><router-link to="usersList" class="nav-item">Přehled uživatelů</router-link></li>
                        <li><router-link to="addUser" class="nav-item">Přidat uživatele</router-link></li>
                      </ul>
                      <ul class="navbar-nav" v-else-if="$root.user.user_type === 'PROFI'">
                        <li><router-link to="puMyInvoice" class="nav-item">Moje faktury</router-link></li>
                        <li><router-link to="puMyContracts" class="nav-item">Moje smlouvy</router-link></li>
                      </ul>
                      <hr class="link-separator" v-if="$root.user.user_type === 'ADMIN'">
                      <ul class="navbar-nav" v-if="$root.user.user_type === 'ADMIN'">
                        <li><router-link to="carBrandManagement" class="nav-item">Správa znacek</router-link></li>
                        <li><router-link to="modelManagementList" class="nav-item">Správa modelů</router-link></li>
                        <li><router-link to="gearManagement" class="nav-item">Správa výbavy</router-link></li>
                      </ul>
                    </div>
                  </div>
                </div>
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
// Komponenta pro výchozí stránku admin prostředí
export default {
  name: 'AdminHome',
  data () {
    return {
      alarm: {
          isAlarm: false,
          type: '',
          AlarmText: ''
        },
      data: {
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
      pocetVlozenychInzeratu: 0,
    }
  },
  methods: {
    /**
    * @vuese
    * Dotaz na API pro získání dat o uživateli
    */
    init(){
          this.$http.get(process.env.API_URL+'v1/open/adv/seller/'+this.$root.user.id_user).then(function(response){
                  this.data.firstname = response.body.jmeno;
                  this.data.lastname = response.body.prijmeni;
                  this.data.adress = response.body.adresa;
                  this.data.city = response.body.mesto;
                  this.data.psc = response.body.psc;
                  this.data.email = response.body.email;
                  this.data.phone = response.body.telefon;
                  this.data.company.isCompany = response.body.is_po;
                  if(response.body.is_po){
                      this.data.company.ic = response.body.ic;
                      this.data.company.name = response.body.spolecnost;
                      this.data.company.adress = response.body.adresapo;
                      this.data.company.city = response.body.mestopo;
                      this.data.company.psc = response.body.pscpo;
                      this.data.company.dic = response.body.dic;
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
                  } else {
                      this.alarm.isAlarm = true;
                      this.alarm.AlarmText = 'Unknow error';
                      this.alarm.type = 'danger';
                  }
          });

          this.getCountOfAdv(); 
    },
    /**
    * @vuese
    * Zažádá o token pro změnu hesla a přesměruje na komponentu resetPass
    */
    changePass(){
          //get token
          let param = {
            id_user: this.$root.user.id_user,
            pass: this.$root.user.pass
          }
          this.$http.post(process.env.API_URL+'v1/reset/change/pass/token', param).then(function (response){
            this.$router.push({ name: 'resetPass', params: {id: this.$root.user.id_user, token: response.body.token, SingIn: true} });
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
    },
    /**
    * @vuese
    * Dotaz na API pro získání počtu vložených inzerátů uživatele
    */
    getCountOfAdv(){
          this.$http.get(process.env.API_URL+'v1/fl/adv/count/'+this.$root.user.id_user,{
                    headers: {
                    'Authorization': 'Bearer '+this.$root.user.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.pocetVlozenychInzeratu = (response.body) ? response.body.pocet : 0;
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
    navbarLeft
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.AdminHome{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
hr.link-separator {
    border: 0;
    height: 1px;
    background: #333;
    background-image: linear-gradient(to right, #ccc, #333, #ccc);
}
</style>
