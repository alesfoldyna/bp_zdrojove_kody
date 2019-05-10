<template>
<div class="advDetails">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div v-if="isBusy.main" class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
            <div v-else class="container mt-3">
              <h3 class="font-weight-light">Podrobnosti inzerártu</h3>
              <hr>
              <router-link v-if="isAdmin" :to="{name: 'userDetail', params: {id_user: $route.params.id_user}}" class="btn btn-secondary mb-3 float-left">Zpět na uživatele</router-link>
              <router-link v-else to="/advList" class="btn btn-secondary mb-3 float-left">Zpět na přehled</router-link>
              <div class="d-flex flex-row-reverse mb-3">
                  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#potvrzeniSmazani">smazat</button>
                  <b-button variant="primary" class="mr-3" v-on:click="goToEdit($route.params.id)">upravit</b-button>   
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
                          Opravdu si přejete smazat tento inzerát? Tento krok nelze vrátit.
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušit</button>
                          <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="deleteAdvertising">Smazat</button>
                      </div>
                      </div>
                  </div>
              </div>
              <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <div class="card bg-light mt-3">
                    <div class="card-header pt-4">
                        <h5 class="font-weight-light">Náhled zveřejněné karty</h5>
                    </div>
                    <div class="card-body">
                        <b-card no-body>
                            <b-tabs card>
                                <b-tab title="Detaily inzerátu" active>
                                    <h4 class="m-3">{{advertisingDetails.znacka}} {{advertisingDetails.model}} {{advertisingDetails.popisek}}</h4>
                                    <div class="row border-bottom m-3"><div class="col-4">Najeto:</div><div class="col-8">{{advertisingDetails.najeto}} Km</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">Vyrobeno:</div><div class="col-8">{{advertisingDetails.vyrobeno}}</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">V provozu od:</div><div class="col-8">{{format.asString('MM/yyyy', new Date(advertisingDetails.provozu_od))}}</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">Cena:</div><div class="col-8 font-weight-bold">{{advertisingDetails.cena}}</div></div>
                                    <div class="row border-bottom m-3">
                                        <div class="col-4">Možnost odpočtu DPH:</div>
                                        <div class="col-8 text-success" v-if="advertisingDetails.odpocet_dph">ANO</div>
                                        <div class="col-8" v-else>Není</div>
                                    </div>
                                    <div class="row border-bottom m-3" v-if="advertisingDetails.odpocet_dph"><div class="col-4">Cena bez DPH:</div><div class="col-8 font-weight-bold">{{advertisingDetails.cena_bez_dph}}</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">VIN:</div><div class="col-8">{{advertisingDetails.vin}}</div></div>
                                    <div class="row border-bottom m-3" v-if="advertisingDetails.text_inzeratu !== null"><div class="col-4">Popis:</div><div class="col-8">{{advertisingDetails.text_inzeratu}}</div></div>
                                    <div class="row border-bottom m-3" v-if="advertisingVybava.length !== 0">
                                      <div class="col-4">Výbava:</div>
                                      <div class="col-8">{{advertisingVybava.toString().replace(/,/g, ', ')}}</div>
                                    </div>
                                </b-tab>
                                <b-tab v-show="advertisingDetails.fotogalerie !== []" title="Fotogalerie">
                                    <b-carousel id="carousel1" style="text-shadow: 1px 1px 2px #333;" controls indicators background="#ababab" :interval="0" img-width="1024" img-height="480">
                                        <b-carousel-slide v-for="(image, index) in images" v-bind:key="index" :data-slide-to="index" :img-src="image.dataUrl"></b-carousel-slide>
                                    </b-carousel>
                                </b-tab>
                            </b-tabs>
                        </b-card>
                    </div>
              </div><!--END card-->
              <div class="card bg-light mt-3 mb-3">
                    <div class="card-header pt-4">
                        <h5 class="font-weight-light">Další informace</h5>
                    </div>
                    <div class="card-body">
                        <div class="row border-bottom m-3">
                            <div class="col-4">Stav:</div>
                            <div class="col-4 text-uppercase text-success" v-if="advertisingDetails.zverejneny">Zveřejněno</div>
                            <div class="col-4 text-uppercase text-danger" v-else>Nezveřejněno (Inzerát bude zveřejněn až po zkontrolování provozovatelem)</div>
                        </div>
                        <div class="row border-bottom m-3">
                            <div class="col-4">Expirace zveřejnění:</div>
                            <div class="col-4" v-if="advertisingDetails.expirace_zverejneni !== null">{{advertisingDetails.expirace_zverejneni}}</div>
                            <div class="col-4 text-uppercase text-danger" v-else-if="seller.user_type === 'NOTPROFI'">Expirační doba bude určena po zveřejnění</div>
                            <div class="col-4 text-uppercase text-success" v-else>Bez expirace</div>
                        </div>
                        <div class="row border-bottom m-3">
                            <div class="col-4">Expirace smazání:</div>
                            <div class="col-4" v-if="advertisingDetails.expirace_smazani !== null">{{advertisingDetails.expirace_smazani}}</div>
                            <div class="col-4 text-uppercase text-danger" v-else-if="seller.user_type === 'NOTPROFI'">Expirační doba bude určena po zveřejnění</div>
                            <div class="col-4 text-uppercase text-success" v-else>Bez expirace</div>
                        </div>
                        <div class="row border-bottom m-3">
                            <div class="col-4">Datum vytvoření:</div>
                            <div class="col-4">{{format.asString('dd.MM.yyyy', new Date(advertisingDetails.datum_vytvoreni))}}</div>
                        </div>
                    </div>
              </div><!--END card-->
            </div><!--END container-->
          </main>
        </div><!--END row-->
      </div><!--END container-fluid-->
  </div><!--END advDetails-->
</template>

<script>
import navbarTop from '@/components/navbarTop';
import navbarLeft from '@/components/navbarLeft';
import { FulfillingBouncingCircleSpinner } from 'epic-spinners';
// Komponenta pro detail o inzerátu
export default {
  name: 'advDetails',
  data () {
    return {
        advertisingDetails: {
            znacka: null,
            model: null,
            popisek: null,
            najeto: null,
            vyrobeno: null,
            provozu_od: null,
            cena: null,
            vin: null,
            text_inzeratu: null, 
        },
        images: [],
        advertisingVybava: [],
        format: require('date-format'),
        alarm: {
          isAlarm: false,
          type: '',
          AlarmText: ''
        },
        seller: {
            user_type: ''
        },
        isAdmin: false,
        isBusy:{
            main: true,
            request: {
                advDetails: true,
                images: true,
                seller: true,
            }
        }
    }
  },
  methods:{
        /**
        * @vuese
        * Dotaz na API pro získání dat k inzerátu
        * @arg id identifikátor inzerátu
        * @arg id_user identifikátor uživatele
        */
        init(id, id_user){
            this.$http.get(process.env.API_URL+'v1/open/adv/details/'+id).then(function(response){
                var formatNajeto = this.$root.numberWithSpaces(response.body.najeto);
                this.advertisingDetails = response.body;
                this.advertisingDetails.najeto = formatNajeto;
                if(response.body.id_vybava !== null){
                    this.$http.get(process.env.API_URL+'v1/open/vybava/'+response.body.id_vybava.replace(/{|}/g,'')).then(function(response){
                        for(var i = 0; i < response.body.length; i++) this.advertisingVybava.push(response.body[i].nazev);
                        this.isBusy.request.advDetails = false;
                        if(!this.isBusy.request.advDetails && !this.isBusy.request.images && !this.isBusy.request.seller) this.isBusy.main = false;
                    });
                } else {
                    this.isBusy.request.advDetails = false;
                        if(!this.isBusy.request.advDetails && !this.isBusy.request.images && !this.isBusy.request.seller) this.isBusy.main = false;
                }
            });
            this.$http.get(process.env.API_URL+'v1/open/getImages/'+id).then(function(response){
                this.images = response.body;
                this.isBusy.request.images = false;
                if(!this.isBusy.request.advDetails && !this.isBusy.request.images && !this.isBusy.request.seller) this.isBusy.main = false;
            });
            this.$http.get(process.env.API_URL+'v1/open/adv/seller/'+id_user).then(function(response){
                this.seller = response.body;
                this.isBusy.request.seller = false;
                if(!this.isBusy.request.advDetails && !this.isBusy.request.images && !this.isBusy.request.seller) this.isBusy.main = false;
            });
        },
        /**
        * @vuese
        * Přesměrování na komponentu EditAdvertisng
        * @arg id identifikátor inzerátu
        */
        goToEdit(id){
            this.$router.push({ name: 'EditAdvertising', params: { id_adver: id }});
        },
        /**
        * @vuese
        * Požadavek do API na smazání inzerátu
        */
        deleteAdvertising(){
            this.$http.delete(process.env.API_URL+'v1/fl/adv/delete/' + this.$route.params.id, {
                    headers: {
                    'Authorization': 'Bearer '+this.$root.user.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    if(this.isAdmin){
                        this.$router.push({ name: 'userDetail', params: { id_user: this.$route.params.id_user, isAlarm: true, AlarmText: 'Inzerát byl smazán', alarmStyle: 'warning'} });
                    } else this.$router.push({ name: 'advList', params: { isAlarm: true, AlarmText: 'Inzerát byl smazán', alarmStyle: 'warning'} });
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
    this.init(this.$route.params.id, this.$route.params.id_user);
    if(this.$route.params.isAdmin) this.isAdmin = true;
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
div.advDetails{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>