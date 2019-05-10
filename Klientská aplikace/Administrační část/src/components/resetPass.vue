<template>
    <div class="resetPass">
        <navbar-top></navbar-top>
        <div class="loginBody container text-center">
            <form class="form-signin mt-3" v-on:submit.prevent="onSubmit">
                    <h1 class="h3 mb-3 font-weight-normal">Nastavení hesla</h1>
                <b-alert :show="isAlarm" variant="danger">{{AlarmText}}</b-alert>
                <div class="form-group">
                    <input type="password" class="form-control" :class="passLabel" id="heslo" placeholder="Heslo" v-model="pass" required>
                    <div class="valid-feedback">Heslo je validní</div>
                    <div class="invalid-feedback">Heslo musí obsahovat malé a velké písmena, číslo a musí mít minimálně 6 znaků</div>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" :class="confirmPassLabel" id="potvrzeniHesla" placeholder="Potvrzení hesla" v-model="confirmPass" required>
                    <div class="valid-feedback">Heslo se stotožňuje</div>
                    <div class="invalid-feedback">Heslo se neshoduje</div>
                </div>
                <button class="btn btn-lg btn-primary btn-block mt-5" type="submit">Změnit heslo</button>
                <div v-show="$route.params.SingIn" class="float-center mt-3"><router-link to="/" class="btn btn-secondary">Zpět</router-link></div>
            </form>
        </div>
    </div>
</template>

<script>
import navbarTop from '@/components/navbarTop';
// Komponenta pro nastavení hesla. 
export default {
    name: 'resetPass',
    data () {
        return {
            pass: '',
            confirmPass: '',
            validconfirm: false,
            validpass: false,
            passhash: '',
            hash: require('js-sha512'),
            isAlarm: false,
            AlarmText: null
        }
    },
    computed: {
        /**
         * @vuese
         * Spravuje popisek pro potvrzení helsa
         */
        confirmPassLabel: function(){
            if(this.pass === ''){
                this.validconfirm = false;
                return '';
            } else if (this.pass !== this.confirmPass) {
                this.validconfirm = false;
                return 'is-invalid';
            } else {
                this.validconfirm = true;
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
            if(this.pass.match(lowerCaseLetters) &&
                this.pass.match(upperCaseLetters) &&
                this.pass.match(numbers) &&
                this.pass.length >= 6) {
                    this.validpass = true;
                    return 'is-valid';
            } else {
                this.validpass = false;
                return 'is-invalid'
            }
        }
    },
    methods: {
        /**
         * @vuese
         * Požadavek do API na změnu hesla
         */
        onSubmit(){
            if(!this.validconfirm || !this.validpass){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'heslo není validní';
                return
            }

            this.passhash = this.hash.sha512(this.pass);

            let logParam = {
                id_user: parseInt(this.$route.params.id),
                pass: this.passhash
            }

            this.$http.put(process.env.API_URL+'v1/reset/pass', logParam, {
                headers: {
                'Authorization': 'Bearer '+this.$route.params.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                if(this.$route.params.SingIn){
                    this.$root.user = {};
                    this.$router.push({ name: 'Login', params: { isAlarm: true, AlarmText: 'Heslo úspěšně změněno. Prosím, přihlašte se s novým heslem', alarmStyle: 'success'} });
                } else this.$router.push({ name: 'Login', params: { isAlarm: true, AlarmText: 'Heslo úspěšně změněno', alarmStyle: 'success'} });
            }, function(response){
                if(response.status === 500){
                    this.isAlarm = true;
                    this.AlarmText = response.body.error.text;
                } else if(response.status === 401){
                    this.isAlarm = true;
                    this.AlarmText = response.body.message;
                }
            });

        }
    },
    created: function(){

    },
    components: {
        navbarTop
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
</style>
