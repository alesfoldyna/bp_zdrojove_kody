<template>
    <div class="AdvertisingShortCard container">
        <div v-if="$root.resultOfSearch.length !== 0">
            <button v-for="inzerat in $root.resultOfSearch[$root.indexOfSearchPage]" v-bind:key="inzerat.id_adver" class="mb-3 p-3 advertisingButton btn-outline-secondary btn-block" data-toggle="modal" data-target=".advertisingDetails" v-on:click="afterClickAdvBtn(inzerat.id_adver)">
                <div class="carta">
                    <b-row align-v="center" align-h="center">
                        <b-col cols="auto"><img class="align-self-center mr-3 uvodniFoto" :src="inzerat.imageUrl" alt="Generic placeholder image"></b-col>
                        <b-col cols="8">
                        <div class="carta-telo">
                            <h5 class="mt-3">{{inzerat.znacka}} {{inzerat.model}} {{inzerat.popisek}}</h5>
                            <b-row>
                                <b-col cols="auto" style="width: 100px">Najeto:</b-col><b-col>{{$root.numberWithSpaces(inzerat.najeto)}} Km</b-col>
                            </b-row>
                            <b-row>
                                <b-col cols="auto" style="width: 100px">Vyrobeno:</b-col><b-col>{{inzerat.vyrobeno}}</b-col>  
                            </b-row>
                            <b-row align-h="end" class="font-weight-bold">
                                {{inzerat.cena}}    
                            </b-row>
                            <b-row v-if="inzerat.odpocet_dph" align-h="end">
                                bez DPH: {{inzerat.cena_bez_dph}}    
                            </b-row>
                        </div>
                        </b-col>
                    </b-row>
                </div>
            </button>

            <div class="d-flex justify-content-center"><vue-paginate-al :totalPage="$root.resultOfSearch.length" @btnClick="changeIndexOfSearch"></vue-paginate-al></div>
        </div>
        <div v-else>
            <div class="jumbotron mt-b">
                <h1 class="display-4">Nic nenalezeno</h1>
                <p class="lead">Bohužel jsme nenašli žádný vyhovující inzerát</p>
                <hr class="my-4">
                <p>Prosím, zkuste změnit požadavky a vyhledat znovu.</p>
            </div>
        </div>

        <div class="modal fade advertisingDetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{advertisingDetails.znacka + " " + advertisingDetails.model + " " + advertisingDetails.popisek}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <b-card no-body>
                            <b-tabs card v-model="tabIndex">
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
                                <b-tab title="O prodejci">
                                    <h6 class="m-3">Prodejce</h6>
                                    <div class="row border-bottom m-3" v-show="seller.is_po"><div class="col-4">Název firmy:</div><div class="col-8">{{seller.spolecnost}}</div></div>
                                    <div class="row border-bottom m-3" v-show="seller.is_po"><div class="col-4">IČ:</div><div class="col-8">{{seller.ic}}</div></div>
                                    <div class="row border-bottom m-3" v-show="seller.is_po"><div class="col-4">DIČ:</div><div class="col-8">{{seller.dic}}</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">Kontaktní osoba:</div><div class="col-8">{{seller.jmeno}} {{seller.prijmeni}}</div></div>
                                    <h6 class="m-3">Adresa</h6>
                                    <div class="row border-bottom m-3"><div class="col-4">Adresa:</div><div class="col-8">{{seller.is_po ? seller.adresapo : seller.adresa}}</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">Mesto:</div><div class="col-8">{{seller.is_po ? seller.mestopo : seller.mesto}}</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">PSC:</div><div class="col-8">{{seller.is_po ? seller.pscpo : seller.psc}}</div></div>
                                    <h6 class="m-3">Kontakt</h6>
                                    <div class="row border-bottom m-3"><div class="col-4">Telefon:</div><div class="col-8">{{seller.telefon}}</div></div>
                                    <div class="row border-bottom m-3"><div class="col-4">E-mail:</div><div class="col-8">{{seller.email}}</div></div>
                                </b-tab>
                            </b-tabs>
                        </b-card>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
import VuePaginateAl from 'vue-paginate-al'
/**
 * @vuese
 * Komponenta pro zobrazení výsledku vyhledávání. Bere data z $root. 
 */
export default {
    name: 'AdvertisingShortCard',
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
                text_inzeratu: null
            },
            seller: {
                is_po: false,
            },
            advertisingVybava: [],
            format: require('date-format'),
            images: [],
            slide: 0,
            tabIndex: 0,
        }
    },
    methods: {
        /**
         * @vuese
         * Změní proměnou, která identifikuje stránku s výsledky. Výsledky se rozdělují po deseti.
         */
        changeIndexOfSearch : function(n){
            this.$root.indexOfSearchPage = n-1;
        },
        /**
         * @vuese
         * Otevře modální okno s detaily inzerátu
         */
        afterClickAdvBtn(id){
            console.log('jsem ve funkci')
            this.advertisingVybava = [];
            this.$http.get(process.env.API_URL+'v1/open/adv/details/'+id).then(function(response){
                var formatNajeto = this.$root.numberWithSpaces(response.body.najeto);
                this.advertisingDetails = response.body;
                this.advertisingDetails.najeto = formatNajeto;
                (response.body.fotogalerie !== null) ? this.advertisingDetails.fotogalerie = response.body.fotogalerie.replace(/{|}/g,'').split(',') : this.advertisingDetails.fotogalerie = [];
                if(response.body.id_vybava !== null){
                    this.$http.get(process.env.API_URL+'v1/open/vybava/'+response.body.id_vybava.replace(/{|}/g,'')).then(function(response){
                        for(var i = 0; i < response.body.length; i++) this.advertisingVybava.push(response.body[i].nazev);
                    });
                }
                this.$http.get(process.env.API_URL+'v1/open/adv/seller/'+response.body.id_user).then(function(response){
                    this.seller = response.body;
                });
                this.slide = 0;
                this.tabIndex = 0;
            });
            this.$http.get(process.env.API_URL+'v1/open/getImages/'+id).then(function(response){
                this.images = response.body;
            });
        }
    }
}
</script>

<style scoped>
    img.uvodniFoto {
        border: 1px solid #ddd ;
        border-radius: 4px;
        padding: 5px;
        width: 200px;
    }
    button.advertisingButton{
        text-align: left;
        border: 1px solid #ddd;
        cursor: pointer;
        outline: none;
    }
    button.advertisingButton:focus{
        box-shadow: none;
    }
    @media (min-width:700px){
        div.carta img{
            float: left;
        }
    }
    @media (max-width:699px){
        div.carta {
            width: 100%
        }
        div.carta img{
            display: block;
            width: 200px;
        }
    }
</style>
