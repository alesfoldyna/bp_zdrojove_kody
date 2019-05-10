<template>
    <div class="conditionsManagementAdd">
        <navbarTop></navbarTop>
        <div class="container-fluid h-100">
            <div class="row h-100">
                <navbarLeft></navbarLeft>
                <main class="col">
                    <div class="container mt-3">
                        <b-row align-h="between" align-v="center"> 
                            <b-col cols="auto"><h1 class="font-weight-light">Přidat všeobecnou podmínku</h1></b-col>
                            <b-col cols="auto"><router-link :to="{name: 'conditionsManagementList'}" class="btn btn-secondary">Zpět</router-link></b-col>
                        </b-row>
                        <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                        <b-card bg-variant="light">
                            <b-form>
                                <b-row>
                                    <b-col>
                                        <b-form-group label-cols-sm="2" label="Platna od:" label-align-sm="right" label-for="platnost_od">
                                            <b-form-input id="platnost_od" type="date" v-model="inputs.platnost_od" required/>
                                        </b-form-group>
                                    </b-col>
                                    <b-col>
                                        <b-form-group label-cols-sm="2" label="Platna do:" label-align-sm="right" label-for="platnost_do">
                                            <b-form-input id="platnost_do" type="date" v-model="inputs.platnost_do"/>
                                            <small id="popisek" class="form-text text-muted" >Pro dobu neurčitou nechte nevyplněné</small>
                                        </b-form-group>
                                    </b-col>
                                </b-row>
                                <b-row>
                                    <b-col>
                                        <b-form-group label-cols-sm="2" label="Platna:" label-align-sm="right" label-for="platna">
                                            <b-form-checkbox id="platna" v-model="inputs.platna">Zaškrtněte, pokud podmínka je již platná</b-form-checkbox>
                                        </b-form-group>
                                    </b-col>
                                </b-row>
                                <h4 class="text-center font-weight-light mt-3 mb-3">Znění podmínky</h4>
                                <b-form-textarea rows="8" id="zneni" v-model="inputs.zneni" placeholder="Napište, nebo zkopírujte znění všeobecné podmínky v HTML"/>
                                <b-button v-on:click="addConditions()" block variant="primary mt-3">Vložit podmínku</b-button>
                            </b-form>
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
// Komponenta pro přidání všeobecné podmínky
export default {
  name: 'conditionsManagementAdd',
  data () {
    return {
        alarm: {
            isAlarm: false,
            type: '',
            AlarmText: ''
        },
        inputs: {
            zneni: null,
            platnost_od: null,
            platnost_do: null,
            platna: true,
        }
    }
  },
  methods: {
      /**
       * @vuese
       * Požadavek do API na přidání všeobecné podmínky
       */
      addConditions(){
          if(this.inputs.zneni === null || this.inputs.platnost_od === null){
            this.alarm.isAlarm = true;
            this.alarm.type = 'danger';
            this.alarm.AlarmText = 'Platnost od a znění všeobecné podmínky musí být vyplněné.';
            return
          }

          let conditionParam = {
              zneni: this.inputs.zneni,
              platnost_od: this.inputs.platnost_od,
              platnost_do: this.inputs.platnost_do,
              platna: this.inputs.platna ? 1 : 0,
          }

          let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
            }
            
            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
                this.$http.post(process.env.API_URL+'v1/admin/conditions/add', conditionParam, {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.$router.push({ name: 'conditionsManagementList', params: { isAlarm: true, AlarmText: 'Podmínka byla úspěšně vložena', alarmStyle: 'success'} });
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

      }
  },
  created: function(){
    this.$root.checkIsLogin();
  },
  components: {
    navbarTop,
    navbarLeft
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.conditionsManagementAdd{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>
