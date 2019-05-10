<template>
    <div class="mainSearch container">
      <div class="card mb-3">
        <div class="card-header">
          <div class="row align-items-center">
            <h5 class="col-md mt-2">Vyhledávaní</h5>
            <div class="d-flex flex-row-reverse bd-highlight col-md"><b-btn v-b-toggle.collapse1 variant="primary" size='sm' v-show="showMainFormCollapseButton">{{mainFormCollapseShow ? 'zasunout' : 'rozbalit'}}</b-btn></div>
          </div>
        </div>
        <b-collapse v-model="mainFormCollapseShow" id="collapse1">
        <form class="card-body">
          <div class="row">
            <div class="form-group col-md">
            <label class="my-1 mr-2" for="selectZnacka">Značka</label>
            <select class="custom-select my-1 mr-sm-2" id="selectZnacka" v-model="$root.search.znacka" v-on:change="loadModels($root.search.znacka), $root.search.model = null, setModelsMadeYears(), $root.search.vybava =[]">
              <option :value="null">Nerozhoduje</option>
              <option v-for="znacka in znacky" :key="znacka.id_znacka" v-bind:value="znacka.id_znacka">{{znacka.nazev}}</option>
            </select>
            </div>
            <div class="form-group col">
            <label class="my-1 mr-2" for="selectModel">Model</label>
            <select class="custom-select my-1 mr-sm-2" id="selectModel" :disabled="$root.search.znacka == null" v-model="$root.search.model" v-on:change="setModelsMadeYears(), loadVybava(), $root.search.vybava =[]">
              <option :value="null">Nerozhoduje</option>
              <option v-for="model in models" :key="model.id_model" v-bind:value="model.id_model">{{model.nazev}}</option>
            </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md">
              <label class="my-1 mr-2" for="selectVyrobenoOd">Vyrobeno od</label>
              <select class="custom-select my-1 mr-sm-2" id="selectVyrobenoOd" v-model="$root.search.vyrobenoOd" v-on:change="setModelsMadeYears()">
                <option :value="null">Nerozhoduje</option>
                <option v-for="od in vyrobenoOd.rate" :key="od" v-bind:value="od">{{od}}</option>
              </select>
              <label class="my-1 mr-2" for="selectVyrobenoDo">Vyrobeno do</label>
              <select class="custom-select my-1 mr-sm-2" id="selectVyrobenoDo" v-model="$root.search.vyrobenoDo">
                <option :value="null">Nerozhoduje</option>
                <option v-for="vdo in vyrobenoDo.rate" :key="vdo" v-bind:value="vdo">{{vdo}}</option>
              </select>
            </div>
            <div class="form-group col-md">
            <label class="my-1 mr-2" for="najetoOd">najeto od</label>
            <select class="custom-select my-1 mr-sm-2" id="najetoOd" v-model="$root.search.najetoOd" v-on:change="makeNajetoRate()">
              <option :value="null">Nerozhoduje</option>
              <option v-for="najetoOd in najeto.od.rate" :key="najetoOd" v-bind:value="najetoOd">{{najetoOd}} Km</option>
            </select>
            <label class="my-1 mr-2" for="najetoDo">najeto do</label>
            <select class="custom-select my-1 mr-sm-2" id="najetoDo" v-model="$root.search.najetoDo">
              <option :value="null">Nerozhoduje</option>
              <option v-for="najetoDo in najeto.do.rate" :key="najetoDo" v-bind:value="najetoDo">{{najetoDo}} Km</option>
            </select>
            </div>
            <div class="form-group col-md">
                <label class="my-1 mr-2" for="cenaOd">cena od</label>
                <select class="custom-select my-1 mr-sm-2" id="cenaoOd" v-model="$root.search.cenaOd" v-on:change="priceCalculate()">
                  <option :value="null">Nerozhoduje</option>
                  <option v-for="cenaOd in cena.od.rate" :key="cenaOd" v-bind:value="cenaOd">{{cenaOd}} Kč</option>
                </select>
                <label class="my-1 mr-2" for="cenaDo">cena do</label>
                <select class="custom-select my-1 mr-sm-2" id="cenaoDo" v-model="$root.search.cenaDo">
                  <option :value="null">Nerozhoduje</option>
                  <option v-for="cenaDo in cena.do.rate" :key="cenaDo" v-bind:value="cenaDo">{{cenaDo}} Kč</option>
                </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md">
              <div class="d-flex flex-row-reverse bd-highlight"><b-btn v-b-toggle.collapse2 variant="secondary" size='sm'>Výbava</b-btn></div>
                <b-collapse id="collapse2">
                  <div class="row mt-3">
                    <div v-for="kategorieVybav in vybava.kategorie" v-bind:key="kategorieVybav.kategorie" class="col-sm">
                    <h4>{{kategorieVybav.kategorie}}</h4>
                    <div v-for="vyb in zobrazeniVybavy(kategorieVybav.kategorie)" v-bind:key="vyb.id_vybava" class="custom-control custom-checkbox mr2" :id="kategorieVybav.kategorie">
                      <input class="custom-control-input" type="checkbox" v-bind:value="vyb.id_vybava" :id="vyb.id_vybava" v-model="$root.search.vybava">
                      <label class="custom-control-label" :for="vyb.id_vybava">{{vyb.nazev}}</label>
                    </div>
                  </div>
                  </div>
                </b-collapse>
            </div>
          </div>
            <b-btn tag="button" v-on:click="searchResulte()" variant="primary" class="btn-lg btn-block">Hledat</b-btn>
        </form>
        </b-collapse>
      </div>
      </div>
</template> 

<script>
// Komponenta pro vyhledávací formulář.
export default {
  name: 'mainSearch',
  data () {
    return {
      znacky: [],
      models: [],
      vybava: {list: [], kategorie: []},
      vyrobenoOd: {possibleFrom: 1950, possibleTo: (new Date()).getFullYear(), rate: []},
      vyrobenoDo: {possibleFrom: 1950, possibleTo: (new Date()).getFullYear(), rate: []},
      najeto: {od: {rate: []}, do: {rate: []}},
      cena: { od: {select: null, rate: []}, do: {select: null, rate: []}},
      mainFormCollapseShow: null,
      showMainFormCollapseButton: null,
      findString: ''
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
        for(var i = 0; i<this.vybava.list.length; i++){
          if(this.vybava.list[i].kategorie === kategorie) result.push(this.vybava.list[i]);
        }
        return result;
    },
    /**
     * @vuese
     * Požadavek do API pro získání všech značek
     */ 
    loadZnacka(){
        // Fire when the form is cleared
        this.$http.get(process.env.API_URL+'v1/open/znacka')
        .then(function(response){
          this.znacky = response.body;
        });
    },
    /**
     * @vuese
     * Požadavek do API pro získání všech modelů příslušné značky
     * @arg id je identifikátor značky.
     */
    loadModels(id){
        this.$http.get(process.env.API_URL+'v1/open/models/'+id)
        .then(function(response){
          this.models = response.body;
        });
    },
    /**
     * @vuese
     * Vytváří pole pro výběr roků výroby
     */
    writeMadeYears(){
      this.vyrobenoOd.rate = [...Array(this.vyrobenoOd.possibleTo - this.vyrobenoOd.possibleFrom+1).keys()].map(i => i + this.vyrobenoOd.possibleFrom).reverse();
      this.vyrobenoDo.rate = [...Array(this.vyrobenoDo.possibleTo - this.vyrobenoDo.possibleFrom+1).keys()].map(i => i + this.vyrobenoDo.possibleFrom).reverse();
    },
    /**
     * @vuese
     * Pokud je ve formuláři zadaný počáteční rozmezí roku výroby a uživatel vybere jiný model, který je mimo zadaný rok, tak zadaný rok vynuluje
     */
    checkSelectVyrobenoOd(){
      if((this.$root.search.vyrobenoOd < this.vyrobenoOd.possibleFrom || this.$root.search.vyrobenoOd > this.vyrobenoOd.possibleTo) && this.$root.search.vyrobenoOd !== null) this.$root.search.vyrobenoOd = null;
    },
    /**
     * @vuese
     * Pokud je ve formuláři zadané konečné rozmezí roku výroby a uživatel vybere jiný model, který je mimo zadaný rok, tak zadaný rok vynuluje
     */
    checkSelectVyrobenoDo(){
      if((this.$root.search.vyrobenoDo < this.vyrobenoDo.possibleFrom || this.$root.search.vyrobenoDo > this.vyrobenoDo.possibleTo) && this.$root.search.vyrobenoDo !== null) this.$root.search.vyrobenoDo = null;
    },
    /**
     * @vuese
     * Připravuje rozmezí pro funkci writeMadeYears
     */
    setModelsMadeYears() {
      if(this.$root.search.model === null){
        this.vyrobenoOd.possibleFrom = 1950;
        this.vyrobenoOd.possibleTo = (new Date()).getFullYear();
        this.checkSelectVyrobenoOd(); 
        (this.$root.search.vyrobenoOd === null) ? this.vyrobenoDo.possibleFrom = 1950 : this.vyrobenoDo.possibleFrom = this.$root.search.vyrobenoOd;
        this.vyrobenoDo.possibleTo = (new Date()).getFullYear();
        this.checkSelectVyrobenoDo();
        this.writeMadeYears();
      } else {
        this.$http.get(process.env.API_URL+'v1/open/model/years/'+this.$root.search.model)
          .then(function(response){
            var vybranyModelRoky = response.body;
            (vybranyModelRoky.vyrobeno_od === null) ? this.vyrobenoOd.possibleFrom = 1950 : this.vyrobenoOd.possibleFrom = vybranyModelRoky.vyrobeno_od;
            (vybranyModelRoky.vyrobeno_do === null) ? this.vyrobenoOd.possibleTo = (new Date()).getFullYear() : this.vyrobenoOd.possibleTo = vybranyModelRoky.vyrobeno_do;
            this.checkSelectVyrobenoOd();
            (this.$root.search.vyrobenoOd === null) ? this.vyrobenoDo.possibleFrom = vybranyModelRoky.vyrobeno_od : this.vyrobenoDo.possibleFrom = this.$root.search.vyrobenoOd;
            (vybranyModelRoky.vyrobeno_do === null) ? this.vyrobenoDo.possibleTo = (new Date()).getFullYear() : this.vyrobenoDo.possibleTo = vybranyModelRoky.vyrobeno_do;
            this.checkSelectVyrobenoDo();
            this.writeMadeYears()
          });
      }
    },
    /**
     * @vuese
     * Vytváří pole pro rozmezí najetých kiometrů
     */
    makeNajetoRate(){
      var firstTempArr = new Array(10); // do 50 000 km po 5 000 km
      var secondTempArr = new Array(5); // do 100 000 km po 10 000 km
      var thirdTempArr = new Array(4); // do 200 000 km po 25 000 km
      var fourthTempArr = new Array(6); // do 500 000 km po 50 000 km

      for(var i = 0; i < firstTempArr.length; i++) firstTempArr[i] = this.$root.numberWithSpaces(5000+5000*i);
      for(var i = 0; i < secondTempArr.length; i++) secondTempArr[i] = this.$root.numberWithSpaces(60000+10000*i);
      for(var i = 0; i < thirdTempArr.length; i++) thirdTempArr[i] = this.$root.numberWithSpaces(125000+25000*i);
      for(var i = 0; i < fourthTempArr.length; i++) fourthTempArr[i] = this.$root.numberWithSpaces(250000+50000*i);

      this.najeto.od.rate = firstTempArr;
      this.najeto.od.rate = this.najeto.od.rate.concat(secondTempArr);
      this.najeto.od.rate = this.najeto.od.rate.concat(thirdTempArr);
      this.najeto.od.rate = this.najeto.od.rate.concat(fourthTempArr);

      (this.$root.search.najetoOd === null) ? this.najeto.do.rate = this.najeto.od.rate : this.najeto.do.rate = this.najeto.od.rate.slice(this.najeto.od.rate.indexOf(this.$root.search.najetoOd));
      if(this.$root.search.najetoDo !== null){
        var select = this.$root.numberWithoutSpaces(this.$root.search.najetoDo)
        var firstIndex = this.$root.numberWithoutSpaces(this.najeto.do.rate[0])
        if(select < firstIndex) this.$root.search.najetoDo = null;
      }
      
    },
    /**
     * @vuese
     * Požadavek do API na výbavy. Pokud není zadaný model, vrátí všechny výbavy. V opačném případě vrátí výbavy přiřazené k příslušnému modelu.
     */
    loadVybava(){
      if(this.$root.search.model === null){
        this.$http.get(process.env.API_URL+'v1/open/vybava')
        .then(function(response){
          this.vybava.list = response.body;
        });
      } else {
        this.$http.get(process.env.API_URL+'v1/open/model/vybava/'+this.$root.search.model)
        .then(function(response){
          var vybavaModelu = response.body.id_vybava.replace(/{|}/g,'');
          this.$http.get(process.env.API_URL+'v1/open/vybava/'+vybavaModelu)
          .then(function(response){
            this.vybava.list = response.body;
          });
        });
      }
    },
    /**
     * @vuese
     * Požadavek do API pro zísání kategorií výbav
     */
    loadKategorieVybav(){
      this.$http.get(process.env.API_URL+'v1/open/vybava/info/kategorie')
      .then(function(response){
        this.vybava.kategorie = response.body;
      });
    },
    /**
     * @vuese
     * Volá funkci preparPriceRate
     */
    priceCalculate(){
      this.preparPriceRate();
    },
    /**
     * @vuese
     * Vytáří pole pro rozmezí cen.
     */
    preparPriceRate(){
      var firstTempArr = new Array(10); // do 50 000 Kc po 5 000 Kc
      var secondTempArr = new Array(5); // do 100 000 Kc po 10 000 Kc
      var thirdTempArr = new Array(4); // do 200 000 Kc po 25 000 Kc
      var fourthTempArr = new Array(6); // do 500 000 Kc po 50 000 Kc
      var fifthTempArr = new Array(5); //do 1 000 000 Kc po 100 000 Kc
      var sixthTempArr = new Array(8); //do 3 000 000 Kc po 250 000 Kc
      var sevnthTempArr = new Array(4); //do 5 000 000 Kc po 500 000 Kc
      var eighthTempArr = new Array(5); //do 10 000 000 Kc po 1 000 000 Kc

      for(var i = 0; i < firstTempArr.length; i++) firstTempArr[i] = this.$root.numberWithSpaces(5000+5000*i);
      for(var i = 0; i < secondTempArr.length; i++) secondTempArr[i] = this.$root.numberWithSpaces(60000+10000*i);
      for(var i = 0; i < thirdTempArr.length; i++) thirdTempArr[i] = this.$root.numberWithSpaces(125000+25000*i);
      for(var i = 0; i < fourthTempArr.length; i++) fourthTempArr[i] = this.$root.numberWithSpaces(250000+50000*i);
      for(var i = 0; i < fifthTempArr.length; i++) fifthTempArr[i] = this.$root.numberWithSpaces(600000+100000*i);
      for(var i = 0; i < sixthTempArr.length; i++) sixthTempArr[i] = this.$root.numberWithSpaces(1250000+250000*i);
      for(var i = 0; i < sevnthTempArr.length; i++) sevnthTempArr[i] = this.$root.numberWithSpaces(3500000+500000*i);
      for(var i = 0; i < eighthTempArr.length; i++) eighthTempArr[i] = this.$root.numberWithSpaces(6000000+1000000*i);

      this.cena.od.rate = firstTempArr;
      this.cena.od.rate = this.cena.od.rate.concat(secondTempArr);
      this.cena.od.rate = this.cena.od.rate.concat(thirdTempArr);
      this.cena.od.rate = this.cena.od.rate.concat(fourthTempArr);
      this.cena.od.rate = this.cena.od.rate.concat(fifthTempArr);
      this.cena.od.rate = this.cena.od.rate.concat(sixthTempArr);
      this.cena.od.rate = this.cena.od.rate.concat(sevnthTempArr);
      this.cena.od.rate = this.cena.od.rate.concat(eighthTempArr);

      (this.$root.search.cenaOd === null) ? this.cena.do.rate = this.cena.od.rate: this.cena.do.rate = this.cena.od.rate.slice(this.cena.od.rate.indexOf(this.$root.search.cenaOd));
      if(this.$root.search.cenaDo !== null){
        var select = this.$root.numberWithoutSpaces(this.$root.search.cenaDo);
        var firstIndex = this.$root.numberWithoutSpaces(this.cena.do.rate[0]);
        if(select < firstIndex) this.$root.search.cenaDo = null;
      }
    },
    /**
     * @vuese
     * Volá globální funkci na vyhledání inzerátů a přesměruje na komponentu SearchResult
     */
    searchResulte(){
      this.$root.loadFindList();
      this.$root.indexOfSearchPage = 0;
      this.$router.push({name: 'search'})
    }
  },
  created: function(){
     this.loadZnacka();
     if(this.$root.search.znacka !== null) this.loadModels(this.$root.search.znacka);
     // pokud je routovací adresa na mainSearch rozbalí vyhledávací pole a nezobrazí tlačítko na zasunutí. V opačném případě okno zabalí a tlačítko zobrazí
     if(this.$route.name === "mainSearch") {
        this.mainFormCollapseShow = true;
        this.showMainFormCollapseButton = false; 
      } else { 
        this.mainFormCollapseShow = false;
        this.showMainFormCollapseButton = true; 
      }
     this.setModelsMadeYears();
     this.makeNajetoRate();
     this.loadVybava();
     this.loadKategorieVybav();
     this.priceCalculate();
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
