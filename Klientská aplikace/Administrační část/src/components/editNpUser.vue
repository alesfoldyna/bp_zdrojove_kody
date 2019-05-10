<template>
<div class="editNpUser">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
                <div class="row">
                    <div class="col"><h3>Upravit účet</h3></div>
                    <div class="col"><div class="float-right"><router-link to="/" class="btn btn-secondary">Zpět</router-link></div></div>
                </div>
                <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <form v-on:submit.prevent="onSubmit">  
                    <div class="card bg-light mt-3">
                    <div class="card-body">
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
                                Zakládáte účet na firmu?
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
// Komponenta pro úpravu soukromého prodejce
export default {
  name: 'editNpUser',
  data () {
    return {
        inputData: {
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
        alarm: {
            isAlarm: false,
            type: 'danger',
            AlarmText: ''
        },
    }
  },
  methods: {
        /**
         * @vuese
         * Požadavek do API na načtení dat o uživateli
         */
        init(){
          this.$http.get(process.env.API_URL+'v1/open/adv/seller/'+this.$root.user.id_user).then(function(response){
                this.inputData.firstname = response.body.jmeno;
                this.inputData.lastname = response.body.prijmeni;
                this.inputData.adress = response.body.adresa;
                this.inputData.city = response.body.mesto;
                this.inputData.psc = response.body.psc;
                this.inputData.email = response.body.email;
                this.inputData.phone = response.body.telefon;
                this.inputData.company.isCompany = response.body.is_po;
                if(response.body.is_po){
                    this.inputData.company.ic = response.body.ic;
                    this.inputData.company.name = response.body.spolecnost;
                    this.inputData.company.adress = response.body.adresapo;
                    this.inputData.company.city = response.body.mestopo;
                    this.inputData.company.psc = response.body.pscpo;
                    this.inputData.company.dic = response.body.dic;
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
        },
        /**
         * @vuese
         * Požadavek do API na úpravu dat uživatele
         */
        onSubmit(){
            let userData = {
                email: this.inputData.email,
                jmeno: this.inputData.firstname,
                prijmeni: this.inputData.lastname,
                adresa: this.inputData.adress,
                mesto: this.inputData.city,
                psc: parseInt(this.inputData.psc),
                telefon: parseInt(this.inputData.phone),
                is_po: this.inputData.company.isCompany? 1 : 0,
            }

            if(this.inputData.company.isCompany){
                let CompanyData = {
                    ic: this.inputData.company.ic,
                    po_nazev: this.inputData.company.name,
                    po_adresa: this.inputData.company.adress,
                    po_mesto: this.inputData.company.city,
                    po_psc: parseInt(this.inputData.company.psc),
                    po_dic: this.inputData.company.dic
                }
                userData = Object.assign(CompanyData, userData);
            }
            this.$http.put(process.env.API_URL+'v1/fl/npu/edit/'+this.$root.user.id_user, userData, {
                headers: {
                'Authorization': 'Bearer '+this.$root.user.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                 this.$router.push({ name: 'AdminHome', params: { isAlarm: true, AlarmText: 'Uživatel úspěšně upraven', alarmStyle: 'success'} });
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
    navbarLeft
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.editNpUser{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>
