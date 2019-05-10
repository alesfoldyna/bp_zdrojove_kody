<template>
<div class="userDetail">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
                <router-link to="/usersList" class="btn btn-secondary mb-3 float-left">Zpět na přehled</router-link>
                <div class="d-flex flex-row-reverse mb-3">
                    <button v-show="$root.user.id_user !== $route.params.id_user" type="button" class="btn btn-danger ml-3" data-toggle="modal" data-target="#potvrzeniSmazani">smazat uživatele</button>
                    <b-button variant="primary" class="" v-on:click="goToEdit($route.params.id_user)">upravit uživatele</b-button>   
                </div>

                <div class="modal fade" id="potvrzeniSmazani" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Zrušení účtu uživatele</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">Opravdu si přejete smazat tohoto uživatele? Tento krok nelze vrátit.</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušit</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="deleteUser($route.params.id_user)">Smazat</button>
                        </div>
                        </div>
                    </div>
                </div>

                <b-alert :show="alarm.isAlarm" :dismissible="alarm.dismessed" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <div class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner v-if="isBusy" class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                <b-card no-body v-if="!isBusy">
                    <b-tabs pills card>
                        <b-tab title="Informace o uživatelovi" active>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Jméno:</b-col><b-col cols="">{{userDetail.jmeno}}</b-col></b-row>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Příjmení:</b-col><b-col cols="">{{userDetail.prijmeni}}</b-col></b-row>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Adresa:</b-col><b-col cols="">{{userDetail.adresa}}</b-col></b-row>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Město:</b-col><b-col cols="">{{userDetail.mesto}}</b-col></b-row>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">PSČ:</b-col><b-col cols="">{{userDetail.psc}}</b-col></b-row>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Telefon:</b-col><b-col cols="">{{userDetail.telefon}}</b-col></b-row>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">email:</b-col><b-col cols="">{{userDetail.email}}</b-col></b-row>
                            <hr>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">ID uživatele:</b-col><b-col cols="">{{userDetail.id_user}}</b-col></b-row>
                            <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Typ uživatele:</b-col><b-col cols="">{{userDetail.user_type}}</b-col></b-row>
                            <b-row align-v="center" v-if="userDetail.user_type === 'PROFI'"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Limit inzerátů:</b-col><b-col cols="">{{userDetail.count_limit}}</b-col></b-row>
                            <b-row align-v="center" v-else-if="userDetail.user_type === 'NOTPROFI'"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Datum posledního přihlášení:</b-col><b-col cols="">{{userDetail.last_login}}</b-col></b-row>
                            <div v-show="userDetail.is_po">
                                <hr>
                                <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Název Firmy:</b-col><b-col cols="">{{userDetail.po_nazev}}</b-col></b-row>
                                <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">IČ:</b-col><b-col cols="">{{userDetail.po_ic}}</b-col></b-row>
                                <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">DIČ:</b-col><b-col cols="">{{userDetail.po_dic}}</b-col></b-row>
                                <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Adresa:</b-col><b-col cols="">{{userDetail.po_adresa}}</b-col></b-row>
                                <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">Město:</b-col><b-col cols="">{{userDetail.po_mesto}}</b-col></b-row>
                                <b-row align-v="center"><b-col cols="2" style="min-width: 200px" class="font-weight-bold">PSČ:</b-col><b-col cols="">{{userDetail.po_psc}}</b-col></b-row>
                            </div>
                        </b-tab>
                        <b-tab title="Přehled inzerátů"><table-of-adv-user :id_user="parseInt($route.params.id_user)" isAdmin></table-of-adv-user></b-tab>
                        <b-tab v-if="(userDetail.user_type === 'PROFI')" title="Faktury">
                            <table-for-invoice :items="userDetail.faktura" editAble v-on:editInvoice="onEditInvoice"></table-for-invoice>
                            <p>
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addInvoice" aria-expanded="false" aria-controls="addInvoice">Přidat fakturu</button>
                            </p>
                            <div class="collapse multi-collapse" id="addInvoice">
                                <b-card bg-variant="light">
                                    <b-form>
                                        <b-row>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Datum vystavení:" label-align-sm="right" label-for="datum_vystaveni">
                                                    <b-form-input id="datum_vystaveni" type="date" v-model="inputsAddInvoice.vystavena" required/>
                                                </b-form-group>
                                            </b-col>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Splatnost:" label-align-sm="right" label-for="splatnost">
                                                    <b-form-input id="splatnost" type="date" v-model="inputsAddInvoice.splatna" required/>
                                                </b-form-group>
                                            </b-col>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Období od:" label-align-sm="right" label-for="obdobi_od">
                                                    <b-form-input id="obdobi_od" type="date" v-model="inputsAddInvoice.obdobi_od" required/>
                                                </b-form-group>
                                            </b-col>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Období do:" label-align-sm="right" label-for="obdobi_do">
                                                    <b-form-input id="obdobi_do" type="date" v-model="inputsAddInvoice.obdobi_do" required/>
                                                </b-form-group>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Částka:" label-align-sm="right" label-for="castka">
                                                    <b-form-input id="castka" type="number" v-model="inputsAddInvoice.castka" required/>
                                                </b-form-group>
                                            </b-col>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="VS:" label-align-sm="right" label-for="vs">
                                                    <b-form-input id="vs" type="number" v-model="inputsAddInvoice.variabilni_symbol" required/>
                                                </b-form-group>
                                            </b-col>
                                        </b-row>
                                        <b-form-group label-cols-sm="2" label="Uhrazena:" label-align-sm="right" label-for="uhrazena">
                                            <b-form-checkbox id="uhrazena" v-model="inputsAddInvoice.zaplacena">Zaškrtněte, jestli je faktura již uhrazená</b-form-checkbox>
                                        </b-form-group>
                                        <b-button v-on:click="onAddInvoice" block variant="primary">Přidat fakturu</b-button>
                                    </b-form>
                                </b-card>
                            </div>
                        </b-tab>
                        <b-tab v-if="(userDetail.user_type === 'PROFI')" title="Smlouvy">
                            <table-for-contracts :items="userDetail.smlouva" editAble v-on:editContract="onEditInvoice"></table-for-contracts>
                            <p>
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addContract" aria-expanded="false" aria-controls="addContract">Přidat smlouvu</button>
                            </p>
                            <div class="collapse multi-collapse" id="addContract">
                                <b-card bg-variant="light">
                                    <b-form>
                                        <b-row>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Platnost od:" label-align-sm="right" label-for="platnost_od">
                                                    <b-form-input id="platnost_od" type="date" v-model="inputsAddContract.platnost_od" required/>
                                                </b-form-group>
                                            </b-col>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Platnost do:" label-align-sm="right" label-for="platnost_do">
                                                    <b-form-input id="platnost_do" type="date" v-model="inputsAddContract.platnost_do"/>
                                                    <small id="popisek" class="form-text text-muted" >Pro dobu neurčitou nechte nevyplněné</small>
                                                </b-form-group>
                                            </b-col>
                                            <b-col>
                                                    <b-form-group label-cols-sm="2" label="Aktivní:" label-align-sm="right" label-for="platna">
                                                        <b-form-checkbox id="platna" v-model="inputsAddContract.platna">Zaškrtněte, jestli je smlouva aktivní</b-form-checkbox>
                                                    </b-form-group>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Výpovězená:" label-align-sm="right" label-for="vypovedni_bodobi">
                                                        <b-form-checkbox id="vypovedni_bodobi" v-model="inputsAddContract.vypovedni_bodobi" v-on:change="inputsAddContract.vypovedni_lhuta = null">Zaškrtněte, je-li smlouva vypovězená</b-form-checkbox>
                                                    </b-form-group>
                                            </b-col>
                                            <b-col>
                                                <b-form-group label-cols-sm="2" label="Výpovědní lhůta:" label-align-sm="right" label-for="vypovedni_lhuta">
                                                    <b-form-input id="vypovedni_lhuta" type="date" v-model="inputsAddContract.vypovedni_lhuta" :disabled="!inputsAddContract.vypovedni_bodobi" required/>
                                                </b-form-group>
                                            </b-col>
                                        </b-row>
                                        <b-button v-on:click="onAddContract" block variant="primary">Přidat smlouvu</b-button>
                                    </b-form>
                                </b-card>
                            </div>
                        </b-tab>
                    </b-tabs>
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
import tableOfAdvUser from '@/components/tableOfAdvUser';
import tableForInvoice from '@/components/tableForInvoice';
import tableForContracts from '@/components/tableForContracts';
import { FulfillingBouncingCircleSpinner } from 'epic-spinners';
// Komponenta s podrobnostmi uživatele.
export default {
  name: 'userDetail',
  data () {
    return {
        alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: '',
          dismessed: true
        },
        userDetail: {},
        inputsAddInvoice: {
            vystavena: null,
            splatna: null,
            obdobi_od: null,
            obdobi_do: null,
            zaplacena: false,
            castka: null,
            variabilni_symbol: null
        },
        inputsAddContract: {
            platnost_od: null,
            platnost_do: null,
            platna: true,
            vypovedni_bodobi: false,
            vypovedni_lhuta: null,
        },
        isBusy: true
    }
  },
  methods: {
      /**
       * @vuese
       * Požadavek do API pro získání dat o uživateli
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
                this.userDetail = response.body;
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
       * Přesměrování na komponentu editUsers
       * @arg id identifikátor uživatele
       */
      goToEdit(id){
          this.$router.push({ name: 'editUser', params: { id_user: id}});
      },
      /**
       * @vuese
       * Požadavek do API na smazání uživatele
       */
      deleteUser(id){
          let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
        }

        this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.delete(process.env.API_URL+'v1/admin/user/delete/'+id, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.$router.push({ name: 'usersList', params: { isAlarm: true, AlarmText: 'Uživatel byla úspěšně vymazán', alarmStyle: 'warning'} });
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
       * Kontrola údajů z formuláře pro přidání smlouvy a následný požadavek do API pro přidání smlouvy
       */
      onAddContract(){
          if(this.inputsAddContract.platnost_od === null){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Smlouva musí mít vyplněnou platnost od';
                this.alarm.type = 'danger';
                return
          }

          if(this.inputsAddContract.vypovedni_bodobi && this.inputsAddContract.vypovedni_lhuta === null){
              this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Je-li zaškrtnute výpovědní období, musí být vyplněna výpovědní lhůta';
                this.alarm.type = 'danger';
                return
          }

          let invoiceParam = {
              id_user: parseInt(this.$route.params.id_user),
              platna_od: this.inputsAddContract.platnost_od,
              platna_do: this.inputsAddContract.platnost_do,
              platna: this.inputsAddContract.platna ? 1 : 0,
              vypovedni_obdobi: this.inputsAddContract.vypovedni_bodobi ? 1 : 0,
              vypovedni_lhuta: this.inputsAddContract.vypovedni_lhuta
          }


          let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }

          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.post(process.env.API_URL+'v1/admin/contract/add', invoiceParam, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Smlouva byla úspěšně přidána';
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
       * Kontrola údajů z formuláře pro přidání faktury a následný požadavek do API pro přidání faktury
       */
      onAddInvoice(){
          let invoiceParam = {
              id_user: parseInt(this.$route.params.id_user),
              vystavena: this.inputsAddInvoice.vystavena,
              splatna: this.inputsAddInvoice.splatna,
              obdobi_od: this.inputsAddInvoice.obdobi_od,
              obdobi_do: this.inputsAddInvoice.obdobi_do,
              zaplacena: this.inputsAddInvoice.zaplacena ? 1 : 0,
              castka: parseFloat(this.inputsAddInvoice.castka),
              variabilni_symbol: parseInt(this.inputsAddInvoice.variabilni_symbol)
          }

          let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }

          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.post(process.env.API_URL+'v1/admin/invoice/add', invoiceParam, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Faktura byla úspěšně přidána';
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
       * Reaktualizace alarmu pro znovu zobrazení
       */
      alarmRefresh() {
        // Remove my-component from the DOM
        this.alarm.isAlarm = false;
        
        this.$nextTick(() => {
          // Add the component back in
          this.alarm.isAlarm = true;
        });
      },
      /**
       * @vuese
       * Informování uživatele o statusu dotazu na úpravu faktury z komponenty tableForInvoice
       */
      onEditInvoice: function(alarm){
            this.alarm.isAlarm = alarm.isAlarm;
            this.alarm.AlarmText = alarm.AlarmText;
            this.alarm.type = alarm.type;
            this.alarmRefresh();
            this.init();
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
    tableOfAdvUser,
    tableForInvoice,
    FulfillingBouncingCircleSpinner,
    tableForContracts
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.userDetail{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>
