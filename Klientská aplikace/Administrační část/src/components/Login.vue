<template>
    <div class="Login">
        <navbar-top></navbar-top>
        <b-modal id="modalVP" ref="modalVP" centered size="lg" @ok="souhlasVP" @hide="odmitnutiVP" title="Změna všeobecných podmínek">
            <p class="text-danger text-center">Nutnost přijetí nové smluvní dokumentace!</p>
            <h4 class="font-weight-light">Znění nové všeobecné podmínky</h4>
            <iframe :srcdoc="vseobecnePodminky.zneni" scrolling class="w-100 mt-3"></iframe>
        </b-modal>
        <div class="loginBody container text-center">
            <form class="form-signin mt-3" v-on:submit.prevent="onSubmit">
                <h1 class="h3 mb-3 font-weight-normal">Přihlášení</h1>
                <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                <div class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner v-show="resetPass.load" class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                <label for="inputEmail" class="sr-only">Email</label>
                <input type="email" id="inputEmail" class="form-control" v-model="email" placeholder="Email" required autofocus>
                <label for="inputPassword" class="sr-only">Heslo</label>
                <input type="password" id="inputPassword" class="form-control" v-model="pass" placeholder="Heslo" required>
                <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">Přihlásit se</button>
                <div>
                    <p class="mt-3 mb-3 text-muted">Zapomněli jste heslo? Klikněte <b-button v-b-modal.modalRP class="m-0 p-0 pb-1" variant="link">zde</b-button>.</p>
                    <b-modal id="modalRP" size="sm" @ok="handleOk" @shown="clearResetPass" title="Změna hesla">
                        <form @submit.stop.prevent="handleOk">
                            <b-form-input v-on:keyup.enter="submit" type="email" placeholder="Zadejte váš email" v-model="resetPass.email" required/>
                        </form>
                    </b-modal>
                </div>
                <p class="mb-3 text-muted">Ještě nemáte účet? Zaregistrujte se <router-link to="addNpUser" class="link">zde</router-link>.</p>
            </form>
        </div>
    </div>
</template>

<script>
import navbarTop from '@/components/navbarTop';
import { FulfillingBouncingCircleSpinner } from 'epic-spinners';
// Komponenta pro přihlášení
export default {
    name: 'Login',
    data () {
        return {
            email: null,
            pass: null,
            hash: require('js-sha512'),
            alarm: {
                isAlarm: false,
                AlarmText: '',
                type: ''
            },
            resetPass: {
                email: null,
                load: false
            },
            vseobecnePodminky: {
                zneni: null
            }
        }
    },
    methods: {
        /**
         * @vuese
         * Pokus o pořihlášení. Dotaz na API na získání tokenu FL skupiny. Při úspěšném přihlášení se přesměruje na komponentu AdminHome.
         */
        onSubmit(){
            let logParam = {
                email: this.email,
                pass: this.hash.sha512(this.pass)
            };

            this.$http.post(process.env.API_URL+'v1/fl/token', logParam).then(function(response){
                this.$root.user = response.body;
                if(response.body.user_type === 'NOTPROFI'){
                    this.$http.get(process.env.API_URL+'v1/open/conditons/isValid/'+ response.body.id_podminka).then(function(response){
                        if(response.body.platna){
                            this.$router.push('/');
                        } else {
                            this.$refs.modalVP.show();
                        }
                    });
                } else this.$router.push('/');
            }, function(response){
                if(response.status === 500){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = response.body.error.text;
                    this.alarm.type = 'danger';
                } else if(response.status === 401){
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = (response.body.message) ? response.body.message : 'Špatné přihlašovací údaje' ;
                    this.alarm.type = 'danger';
                } else {
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Unknow error';
                    this.alarm.type = 'danger';
                }
                this.pass = null;
            });
        },
        /**
         * @vuese
         * Nuluje proměnou pro zadání emailu na obnovení hesla. 
         */
        clearResetPass(){
            this.resetPass.email = null;
        },
        /**
         * @vuese
         * Požadavek na API pro zaslání tokenu na zapomenuté heslo
         */
        handleOk(){
            this.$root.$emit('bv::hide::modal','modalRP');
            if(this.resetPass.email === null || this.resetPass.email === '') return;
            this.isAlarm = false;
            this.resetPass.load = true;

            let resetParam = {
                email: this.resetPass.email
            }

            this.$http.post(process.env.API_URL+'v1/reset/token', resetParam).then(function(response){
                this.alarm.AlarmText = 'Na zadaný email byl odeslán odkaz pro změnu hesla. Odkaz bude platný 10 minut.';
                this.alarm.type = 'success';
                this.alarm.isAlarm = true;
                this.resetPass.load = false;
            }, function(response){
                if(response.status === 500){
                    this.alarm.AlarmText = response.body.error.text;
                    this.alarm.type = 'danger';
                    this.alarm.isAlarm = true;
                    this.resetPass.load = false;
                }
            });
        },
        /**
         * @vuese
         * Požadavek na API pro získání poslední aktualní podmínky
         */
        getVseobecnePdominky(){
            this.$http.get(process.env.API_URL+'v1/open/vseobecnePodminky/aktualni').then(function(response){
                this.vseobecnePodminky = response.body;
            });
        },
        /**
         * @vuese
         * Požadavek na API pro aktualizaci podmínky soukromého prodejce
         */
        souhlasVP(){
            let conditionParam = {
                id_podminka: this.vseobecnePodminky.id_podminka,
                id_user: this.$root.user.id_user
            }

            this.$http.put(process.env.API_URL+'v1/fl/conditions/accept', conditionParam, {
                headers: {
                'Authorization': 'Bearer '+this.$root.user.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Podmínka úspěšně přijata. Prosím, přihlašte se znovu.';
                this.alarm.type = 'success';
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
                console.log(response);
            });
        },
        /**
         * @vuese
         * Výpis informace, že bez přijetí nové podmínky se nelze přihlásit
         */
        odmitnutiVP(){
            this.$root.user = {};
            this.alarm.AlarmText = 'Bez přijetí nové smluvní dokumentace se nelze přihlásit';
            this.alarm.type = 'danger';
            this.alarm.isAlarm = true;
            this.pass = null;
        }
    },
    created: function(){
        this.getVseobecnePdominky();
        if(this.$route.params.isAlarm){
            this.alarm.AlarmText = this.$route.params.AlarmText;
            this.alarm.type = this.$route.params.alarmStyle;
            this.alarm.isAlarm = this.$route.params.isAlarm;
        }
    },
    components: {
        navbarTop,
        FulfillingBouncingCircleSpinner
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    div.loginBody {
        max-width: 300px;
    }
    input {
        margin-bottom: 3px; 
    }
    iframe {
        margin-top: 20px;
        margin-bottom: 30px;
        border: none;
        background-color: white;
        max-height: 300px;
    } 
</style>
