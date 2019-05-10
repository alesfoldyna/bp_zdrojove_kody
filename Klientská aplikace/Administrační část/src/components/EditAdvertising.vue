<template>
<div class="addAdvertising">
  <navbarTop></navbarTop>
  <div class="container-fluid h-100">
    <div class="row h-100">
      <navbarLeft></navbarLeft>
      <main class="col">
      <div class="container mt-3">
        <h1 class="font-weight-light">Úprava inzerátu</h1>
        <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
          <form v-on:submit.prevent="EditAdvertising">
              <div class="card bg-light mt-3"><!--Start card Hlavička Inzerátu-->
                <div class="card-header" id="headingFour">
                  <h2 class="mb-0 lead">
                      Hlavička Inzerátu
                  </h2>
                </div>
                <div id="collapseOne" class="" aria-labelledby="headingOne" data-parent="#accordionExample">
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md">
                        <label class="my-1 mr-2" for="selectZnacka">Značka</label>
                        <select class="custom-select my-1 mr-2" id="selectZnacka" v-model="inputs.znacka" v-on:change="loadModels(inputs.znacka), inputs.model = null, setModelsMadeYears(), inputs.vybava = [], select.vybava = null, nullProvozu_od()" required>
                          <option :value="null">Nevybrano</option>
                          <option v-for="znacka in select.znacky" :key="znacka.id_znacka" v-bind:value="znacka.id_znacka">{{znacka.nazev}}</option>
                        </select>
                      </div>
                      <div class="form-group col-md">
                        <label class="my-1 mr-2" for="selectModel">Model</label>
                        <select class="custom-select my-1 mr-2" id="selectModel" :disabled="inputs.znacka == null" v-model="inputs.model" v-on:change="setModelsMadeYears(), loadVybava(), inputs.vybava = [], select.vybava = null, nullProvozu_od()" required>
                          <option :value="null">Nevybrano</option>
                          <option v-for="model in select.models" :key="model.id_model" v-bind:value="model.id_model">{{model.nazev}}</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md">
                        <label class="my-1 mr-2" for="popisek">Popisek</label>
                        <input class="form-control my-1 mr-2" type="text" id="popisek" :disabled="inputs.model == null" v-model="inputs.popisek" maxlength="60">
                        <small id="popisek" class="form-text text-muted" >Maximálně 60 znaků</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!--End Card Hlavicka inzeratu-->

              <div class="card bg-light mt-3"><!--Start card Informace-->
                <div class="card-header" id="headingFour">
                  <h2 class="mb-0 lead">
                      Informace o vozidle
                  </h2>
                </div>
                    <div id="collapseTwo" class="" aria-labelledby="headingTwo" data-parent="#accordionExample">
                      <div class="card-body">
                        <div class="row vyroba">
                          <div class="form-group col-md-3">
                            <label class="my-1 mr-2" for="selectVyrobeno">Rok výroby</label>
                            <select class="custom-select my-1 mr-2" id="selectVyrobeno" v-model="inputs.vyrobeno" :disabled="inputs.model == null" v-on:change="calProvozuOd()" required>
                              <option :value="null">Nevybrano</option>
                              <option v-for="vyrobeno in select.vyrobeno" :key="vyrobeno" v-bind:value="vyrobeno">{{vyrobeno}}</option>
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label class="my-1 mr-2" for="vProvozuMesic">V provozu od</label>
                            <div class="input-group-prepend" id="button-addon3">
                              <select class="custom-select my-1 mr-2 vProvozuOd" id="selectVyrobeno" v-model="inputs.provozu_od.mesic" :disabled="inputs.vyrobeno == null" v-on:change="getDateProvozuOd()" required>
                                <option :value="null">Měsíc</option>
                                <option v-for="mesic in select.provozu_od.mesic" :key="mesic" v-bind:value="mesic">{{mesic}}</option>
                              </select>
                              <select class="custom-select my-1 mr-2 vProvozuOd" id="selectVyrobeno" v-model="inputs.provozu_od.rok" :disabled="inputs.vyrobeno == null" v-on:change="getDateProvozuOd()" required>
                                <option :value="null">Rok</option>
                                <option v-for="rok in select.provozu_od.rok" :key="rok" v-bind:value="rok">{{rok}}</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-md-3">
                            <label class="my-1 mr-2" for="najeto">Najeto (Km)</label>
                            <input type="number" min="0" class="form-control my-1 mr-2" id="najeto" v-model="inputs.najeto">
                          </div>
                        </div>
                        <div class="row vin">
                          <div class="form-group col-md">
                            <label class="my-1 mr-2" for="vin">VIN</label>
                            <input class="form-control my-1 mr-2" type="text" id="vin" v-model="inputs.vin" maxlength="60" required>
                          </div>
                        </div>
                        <div class="row text">
                          <div class="form-group col-md">
                            <label class="my-1 mr-2" for="textInzeratu">Další informace o inzerátu</label>
                            <textarea class="form-control" id="textInzeratu" rows="3" v-model="inputs.textInzeratu"></textarea>
                          </div>
                        </div>
                        <div class="row cena">
                          <div class="form-group col-md">
                            <label class="my-1 mr-2" for="cena">Cena</label>
                            <div class="input-group-prepend">
                              <div class="input-group mb-3" style="width: 200px">
                                <input type="number" class="form-control" id="cena" min="0" v-model="inputs.cena" v-on:change="calculatePriceWithoutDuty()" required>
                                <div class="input-group-append">
                                  <span class="input-group-text" id="basic-addon2">Kč</span>
                                </div>
                              </div>
                              <div class="custom-control custom-checkbox mr2">
                                <input class="custom-control-input" type="checkbox" id="odpocetDPH" v-model="inputs.odpocet_dph" v-on:change="calculatePriceWithoutDuty()">
                                <label class="custom-control-label ml-5 mt-2 mr-5" for="odpocetDPH">Možnost odpočtu DPH</label>
                              </div>
                            </div>
                          </div>
                        </div><!--end row-->
                        <div v-show="inputs.odpocet_dph" class="row">
                          <div class="form-group col-md">
                            <label class="my-1 mr-2 mt-2" for="cenaBezDPH" >Cena bez DPH:</label>
                            <div class="input-group mb-3" style="width: 200px">
                              <input type="number" class="form-control" id="cenaBezDPH" v-model="inputs.cena_bez_dph" readonly disabled>
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Kč</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div><!--end Card body-->
                    </div><!--end collapse-->
              </div><!--End Card Informace o inzeratu-->

              <div class="card bg-light mt-3"><!--Start card vybava-->
                <div class="card-header" id="headingFour">
                  <h2 class="mb-0 lead">
                      Výbava
                  </h2>
                </div>
                    <div id="collapseThree" class="" aria-labelledby="headingThree" data-parent="#accordionExample"><!--Start collapse-->
                      <div class="card-body"><!--Start card-body-->
                        <div class="row mt-3" v-if="select.vybava !== null"><!--Start row-->
                          <div v-for="kategorieVybav in select.kategorieVybav" v-bind:key="kategorieVybav.kategorie" class="col-sm"><!--Start col-sm-->
                            <h4>{{kategorieVybav.kategorie}}</h4>
                            <div v-for="vyb in zobrazeniVybavy(kategorieVybav.kategorie)" v-bind:key="vyb.id_vybava" class="custom-control custom-checkbox mr2" :id="kategorieVybav.kategorie"><!--Start custom-checkbox-->
                              <input class="custom-control-input" type="checkbox" v-bind:value="vyb.id_vybava" :id="vyb.id_vybava" v-model="inputs.vybava">
                              <label class="custom-control-label" :for="vyb.id_vybava">{{vyb.nazev}}</label>
                            </div><!--End custom-checkbox-->
                          </div><!--End custom-col-sm-->
                        </div><!--End row-->
                        <div class="neniVybava" v-else>
                          <h4 class="font-weight-light">Nejdříve musíte vybrat model vozidla</h4>
                        </div>
                      </div><!--End card-body-->
                    </div><!--End collapse-->
              </div><!--End Card Výbava-->

              <div class="card bg-light mt-3"><!--Start card fotogalerie-->
                <div class="card-header" id="headingFour">
                  <h2 class="mb-0 lead">
                      Fotogalerie
                  </h2>
                </div>
                <div id="collapseFour" class="pb-3" aria-labelledby="headingFour" data-parent="#accordionExample">
                    <div class="card-body">
                      <div v-for="(item, index) in image" :key="index">
                        <my-image-uploader v-if="renderComponent" :class="'d-flex justify-content-center myUploadrer'+index"
                            :id="'fileInput'+index"
                            :preview="true"
                            :indexOfArr="index"
                            :className="['fileinput', { 'fileinput--loaded': hasImage[index] }]"
                            capture="environment"
                            :maxWidth="512"
                            :debug="1"
                            doNotResize="gif"
                            :autoRotate="true"
                            outputFormat="verbose"
                            :setImagePreview="(image[index] === 'free') ? null : image[index]"
                            @input="setImage"
                            @onDelete="deleteImage"
                        >              
                        </my-image-uploader>
                      </div>
                    </div><!--End card-body-->
                </div><!--End collapseFour-->
              </div><!--End card fotogalerie-->
            <button class="btn btn btn-primary btn-lg btn-block mt-3 mb-3"  type="submit" >Upravit inzerát</button>
          </form>
        </div><!--End container mt-3-->
      </main>
    </div><!--End row h-100-->
  </div><!--End container-fluid-->
</div><!--End addAdvertising-->
</template>

<script>
import navbarTop from '@/components/navbarTop';
import navbarLeft from '@/components/navbarLeft';
import MyImageUploader from '@/components/myUploderImages';
// Komponenta pro úpravu inzerátu
export default {
  name: 'addAdvertising',
  data () {
    return {
        renderComponent: true,
        selectedFile: null,
        image: null,
        hasImage: [false],
        alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: ''
        },
        select:{
          znacky: null,
          models: null,
          vyrobeno: null,
          vybava: null,
          provozu_od: {
            mesic: null,
            rok: null,
          },
          kategorieVybav: null,
        },
        inputs:{
          znacka: null,//notnull
          model: null,//notnull
          vybava: [],
          popisek: null,
          textInzeratu: null,
          cena: null,//notnull
          cena_bez_dph: null,
          vyrobeno: null,//notnull
          najeto: null, 
          provozu_od: {
            mesic: null,
            rok: null,
            date: null//notnull
          },
          vin: null,
          fotogalerie: null,
          odpocet_dph: false//notnull
        }
    }
  },
  methods: {
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
     * Načítá všechny značka z databáze.
     */
    loadZnacka(){
        this.$http.get(process.env.API_URL+'v1/open/znacka')
        .then(function(response){
          this.select.znacky = response.body;
        });
    },
    /**
     * @vuese
     * Načítá modely k příslušné značce.
     * @arg id je identifikátor značky.
     */
    loadModels(id){
        this.$http.get(process.env.API_URL+'v1/open/models/'+id)
        .then(function(response){
          this.select.models = response.body;
        });
    },
    /**
     * @vuese
     * Generuje pole od 1 do 12. Používá se na výběr měsíce, od kdy je automobil v provozu
     */
    makeMounthRange(){
      this.select.provozu_od.mesic = new Array(12)
      for(var i = 0; i < this.select.provozu_od.mesic.length; i++) this.select.provozu_od.mesic[i] = i+1;
    },
    /**
     * @vuese
     * Generuje pole o dvou prvcích. První prvek je rok výroby a druhý je rok výroby + 1. Pole je základem selektu pro rok, od kdy je vozidlo v provozu
     */
    calProvozuOd(){
      if(this.inputs.vyrobeno === null){
        this.select.provozu_od.rok = null;
      } else {
        this.select.provozu_od.rok = [this.inputs.vyrobeno, this.inputs.vyrobeno+1];
      }
    },
    /**
     * @vuese
     * Generuje string z výběru pro datum, od kdy je auto v provozu. Tento string se zapisuje do databáze.
     */
    getDateProvozuOd(){
      if(this.inputs.provozu_od.mesic === null || this.inputs.provozu_od.rok === null) {
        this.inputs.provozu_od.date = null;
      } else {
        this.inputs.provozu_od.date = this.inputs.provozu_od.rok + '-' + this.inputs.provozu_od.mesic + '-01'
      }
    },
    /**
     * @vuese
     * Počítá cenu bez DPH s pevnou sazbou 21 procent.
     */
    calculatePriceWithoutDuty(){
      if(!this.inputs.odpocet_dph){
        this.inputs.cena_bez_dph = null;
        return;
      }
      this.inputs.cena_bez_dph = Math.round((this.inputs.cena / 1.21) * 100) / 100;
    },
    /**
     * @vuese
     * Upravuje možnosti pro roky výroby podle vybraného modelu.
     */
    setModelsMadeYears(){
      if(this.inputs.model === null){
        this.select.vyrobeno = null;
        this.inputs.vyrobeno = null;
        return
      }
      this.$http.get(process.env.API_URL+'v1/open/model/years/'+this.inputs.model)
        .then(function(response){
          if(response.body.vyrobeno_do === null){
            var actualYear = (new Date()).getFullYear();
            this.select.vyrobeno = [...Array(actualYear - response.body.vyrobeno_od+1).keys()].map(i => i + response.body.vyrobeno_od).reverse();
          } else {
            this.select.vyrobeno = [...Array(response.body.vyrobeno_do - response.body.vyrobeno_od+1).keys()].map(i => i + response.body.vyrobeno_od).reverse();
          }
          if(this.select.vyrobeno !== null){
           if(!this.select.vyrobeno.includes(this.inputs.vyrobeno)){
             this.inputs.vyrobeno = null;
           }
        }
        });
    },
    /**
     * @vuese
     * Načítá možné výbavy modelu.
     */
    loadVybava(){
      if(this.inputs.model === null){
        this.select.vybava = null;
        return
      }
       this.$http.get(process.env.API_URL+'v1/open/model/vybava/'+this.inputs.model)
        .then(function(response){
          var vybavaModelu = response.body.id_vybava.replace(/{|}/g,'');
          this.$http.get(process.env.API_URL+'v1/open/vybava/'+vybavaModelu)
          .then(function(response){
            this.select.vybava = response.body;
          });
        });
    },
    /**
     * @vuese
     * Načítá kategorie výbav (Vytváří se podle nich sloupce).
     */
    loadKategorieVybav(){
      this.$http.get(process.env.API_URL+'v1/open/vybava/info/kategorie')
      .then(function(response){
        this.select.kategorieVybav = response.body;
      });
    },
    /**
     * @vuese
     * Přinutí komponentu znovu načíst (používá se při manipulaci s obrázky)
     */
    forceRerender() {
        this.renderComponent = false;
        this.$nextTick(() => {
          this.renderComponent = true;
        });
    },
    /**
     * @vuese
     * Při přidání obrázku zvětší pole s obrázky o jeden a přidá data o uploudovaném obrázku.
     * @arg output výstupní proměnná z komponenty myUploaderImages
     */
    setImage: function(output) {
      this.selectedFile = output;
      if(this.image[this.selectedFile.indexOfArr]==='free')this.image.push('free');
      if(!this.hasImage[this.selectedFile.indexOfArr]) this.hasImage.push(false);
      this.image[this.selectedFile.indexOfArr] = this.selectedFile;
      this.hasImage[this.selectedFile.indexOfArr] = true;
      this.forceRerender();
    },
    /**
     * @vuese
     * Smaže obrázek z pole obrazků na příslušném indexu
     * @arg indexOfArr index obrázku.
     */
    deleteImage: function(indexOfArr){
      this.image.splice(indexOfArr,1);
      this.hasImage.splice(indexOfArr,1);

      this.forceRerender();
    },
    /**
     * @vuese
     * Pošle požadavek do API na úpravu inzerátu
     */
    EditAdvertising(){
      let prepareParamForm = {
        id_znacka: this.inputs.znacka,
        id_model: this.inputs.model,
        id_vybava: (this.inputs.vybava.length !== 0) ? this.inputs.vybava.toString() : null,
        popisek: this.inputs.popisek,
        text_inzeratu: this.inputs.textInzeratu,
        cena: this.inputs.cena,
        cena_bez_dph: (this.inputs.cena_bez_dph !== null) ? this.inputs.cena_bez_dph.toString().replace('.',',') : null,
        vyrobeno: this.inputs.vyrobeno,
        provozu_od: this.inputs.provozu_od.date,
        najeto: this.inputs.najeto,
        vin: this.inputs.vin,
        odpocet_dph: this.inputs.odpocet_dph ? 1 : 0,
        photo: this.makePictureArray()
      }
      this.$http.put(process.env.API_URL+'v1/fl/adv/edit/'+this.$route.params.id_adver, prepareParamForm, {
                headers: {
                'Authorization': 'Bearer '+this.$root.user.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.$router.push({ name: 'advList', params: { isAlarm: true, AlarmText: 'Inzerát byl úspěšně editován', alarmStyle: 'success'} });
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
    },
    /**
     * @vuese
     * Upraví pole obrázků na formát, který se posílá do API
     */
    makePictureArray(){
      if(this.image.length === 1) return null
      var result = [];
      for(var i = 0; i < this.image.length - 1; i++){
        let imageObject = {
          dataUrl: this.image[i].dataUrl,
          index: i+1
        }
        result.push(imageObject);
      }
      return result;
    },
    /**
     * @vuese
     * Požadavek do API na získání informací o inzerátu
     */
    init(){
        this.$http.get(process.env.API_URL+'v1/fl/adv/dataForEdit/'+this.$route.params.id_adver, {
                headers: {
                'Authorization': 'Bearer '+this.$root.user.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.inputs.znacka = response.body.id_znacka;
                this.inputs.model = response.body.id_model;
                this.inputs.vybava = (response.body.id_vybava !== null) ? response.body.id_vybava.replace('{','').replace('}','').split(',') : [];
                this.inputs.popisek = response.body.popisek;
                this.inputs.textInzeratu = response.body.text_inzeratu;
                this.inputs.cena = response.body.cena_number;
                this.inputs.cena_bez_dph = response.body.cena_bez_dph_number;
                this.inputs.vyrobeno = response.body.vyrobeno;
                this.inputs.najeto = response.body.najeto;
                this.inputs.vin = response.body.vin;
                this.inputs.odpocet_dph = response.body.odpocet_dph;
                this.inputs.provozu_od.date = response.body.provozu_od;
                this.inputs.provozu_od.mesic = parseInt(response.body.provozu_od.split('-')[1]);
                this.inputs.provozu_od.rok = parseInt(response.body.provozu_od.split('-')[0]);
                this.image = (response.body.imageSurces !== null) ? response.body.imageSurces : [];

                this.image.push('free');
                console.log('init pred loadModels: '+response.body.id_znacka);
                this.loadModels(response.body.id_znacka);
                this.calProvozuOd();
                this.setModelsMadeYears();
                this.loadVybava();
                this.forceRerender();
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
    },
    /**
     * @vuese
     * Nuluje data uvedení do provozu
     */
    nullProvozu_od(){
      this.inputs.provozu_od.mesic = null;
      this.inputs.provozu_od.rok = null;
      this.inputs.provozu_od.date = null;
    }
  },
  created: function(){
    //check login
    this.$root.checkIsLogin();
    // init function
    this.loadZnacka();
    this.makeMounthRange();
    this.loadKategorieVybav();
    this.init();
  },
  components: {
    navbarTop,
    navbarLeft,
    MyImageUploader
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.addAdvertising{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
div.vProvozuOd{
  width: 30px
}
</style>
