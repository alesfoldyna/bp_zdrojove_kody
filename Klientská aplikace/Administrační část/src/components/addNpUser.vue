<template>
    <div class="addNpUser">
        <navbar-top></navbar-top>
        <div class="container mt-3">
            <div class="row">
                <div class="col"><h3 class="font-weight-light">Vytvoření účtu</h3></div>
                <div class="col"><div class="float-right"><router-link to="login" class="btn btn-secondary">Zpět na přihlášení</router-link></div></div>
            </div>
            <b-alert :show="alarm.isAlarm" variant="danger">{{alarm.AlarmText}}</b-alert>
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
                        <h5 class="card-title">Zabezpečení</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="heslo">Heslo</label>
                            <input type="password" class="form-control" :class="passLabel" id="heslo" placeholder="zadejte helso" v-model="inputData.pass.password" required>
                            <div class="valid-feedback">Heslo je validní</div>
                            <div class="invalid-feedback">Heslo musí obsahovat malé a velké písmena, číslo a musí mít minimálně 6 znaků</div>
                            </div>
                            <div class="form-group col-md-6">
                            <label for="potvrzeniHesla">Potvrzení hesla</label>
                            <input type="password" class="form-control" :class="confirmPassLabel" id="potvrzeniHesla" placeholder="zadejte heslo znovu" v-model="inputData.pass.confirmPass" required>
                            <div class="valid-feedback">Heslo se stotožňuje</div>
                            <div class="invalid-feedback">Heslo se neshoduje</div>
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
                        <hr>
                        <div>
                            <b-button v-b-modal.modalVP variant="link">Všeobecné podmínky</b-button>
                            <!-- vseobecne podminky modal -->
                            <b-modal id="modalVP" centered size="lg" @ok="souhlasVP" title="Všeobecné podmínky">
                                <iframe :srcdoc="vseobecnePodminky.load.zneni" scrolling class="w-100"></iframe>
                            </b-modal>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="vseobecnéPodmínky" v-model="vseobecnePodminky.souhlas" required>
                            <label class="form-check-label" for="vseobecnéPodmínky">
                                Přečetl jsem si a souhlasím se všeobecnými podmínkami.
                            </label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-block btn-primary mt-3 mb-3">Vytvořit účet</button>
            </form>
        </div>
    </div>
</template>

<script>
import navbarTop from '@/components/navbarTop';

// Komponenta pro přidání soukromého prodejce
export default {
    name: 'addNpUser',
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
                pass: {
                    password: '',
                    confirmPass: '',
                    hash: require('js-sha512'),
                    passhash: null,
                    validpass: false,
                    validconfirm: false
                },
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
            vseobecnePodminky: {
                load: {
                    zneni: null
                },
                souhlas: false        
            },
            alarm: {
                isAlarm: false,
                AlarmText: ''
            }
        }
    },
    computed: {
        /**
         * @vuese
         * Spravuje popisek pro potvrzení helsa
         */
        confirmPassLabel: function(){
            if(this.inputData.pass.password === ''){
                this.inputData.pass.validconfirm = false;
                return '';
            } else if (this.inputData.pass.password !== this.inputData.pass.confirmPass) {
                this.inputData.pass.validconfirm = false;
                return 'is-invalid';
            } else {
                this.inputData.pass.validconfirm = true;
                return 'is-valid';
            }
        },
        /**
         * @vuese
         * Spravuje popisek helsa a informuje o dostatečných náležitostech.
         */
        passLabel: function (){
            var lowerCaseLetters = /[a-z]/g;
            var upperCaseLetters = /[A-Z]/g;
            var numbers = /[0-9]/g;
            if(this.inputData.pass.password.match(lowerCaseLetters) &&
                this.inputData.pass.password.match(upperCaseLetters) &&
                this.inputData.pass.password.match(numbers) &&
                this.inputData.pass.password.length >= 6) {
                    this.inputData.pass.validpass = true;
                    return 'is-valid';
            } else {
                this.inputData.pass.validpass = false;
                return 'is-invalid'
            }
        }
    },
    methods: {
        /**
         * @vuese
         * Dotaz do API pro získání poslední aktuální podmínky
         */
        getVseobecnePdominky(){
            this.$http.get(process.env.API_URL+'v1/open/vseobecnePodminky/aktualni').then(function(response){
                this.vseobecnePodminky.load = response.body;
            });
        },
        /**
         * @vuese
         * Vyjádření souhlasu s podmínkami
         */
        souhlasVP(){
            this.vseobecnePodminky.souhlas = true;
        },
        /**
         * @vuese
         * Požadavek do API na přidání uživatele
         */
        addUser(){
            let userData = {
                email: this.inputData.email,
                pass: this.inputData.pass.passhash,
                jmeno: this.inputData.firstname,
                prijmeni: this.inputData.lastname,
                adresa: this.inputData.adress,
                mesto: this.inputData.city,
                psc: parseInt(this.inputData.psc),
                telefon: parseInt(this.inputData.phone),
                is_po: this.inputData.company.isCompany? 1 : 0,
                id_podminka: parseInt(this.vseobecnePodminky.load.id_podminka)
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
            this.$http.post(process.env.API_URL+'v1/open/addNpUser', userData).then(function(response){
                // login
                let logParam = {
                    email: this.inputData.email,
                    pass: this.inputData.pass.passhash
                };
                this.$http.post(process.env.API_URL+'v1/fl/token', logParam).then(function(response){
                    this.$root.user = response.body;
                    this.$router.push('/');
                });
            },function(response){
                this.alarm.isAlarm = true;
                if(response.status === 424) {
                    this.alarm.AlarmText = 'Email je již registrován';
                } else if(response.status === 500) {
                    this.alarm.AlarmText = 'Chyba při ukládání uživatele: '+ response.body.error.text;
                } else this.alarm.AlarmText = 'Nespecifikovana chyba';
            });
        },
        /**
         * @vuese
         * Kontroluje správnost údajů ve formuláři a vyvolá funkci addUser
         */
        onSubmit(){
            //kontrola spravnosti hesla
            if(!this.inputData.pass.validconfirm || !this.inputData.pass.validpass){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Nesprávně zadané heslo';
                return
            }
            this.inputData.pass.passhash = this.inputData.pass.hash.sha512(this.inputData.pass.password);
            //addUser to DB
            this.addUser()
        }
    },
    created: function(){
        this.getVseobecnePdominky();
    },
    components: {
        navbarTop
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.textVP {
    min-height: 300px; 
    max-height: 500px; 
    overflow: scroll
}
iframe {
margin-top: 20px;
margin-bottom: 30px;
border: none;
background-color: white;
max-height: 300px;
} 
</style>
