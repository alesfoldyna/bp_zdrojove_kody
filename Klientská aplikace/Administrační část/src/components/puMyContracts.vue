<template>
    <div class="puMyContracts">
        <navbarTop></navbarTop>
        <div class="container-fluid h-100">
            <div class="row h-100">
                <navbarLeft></navbarLeft>
                <main class="col">
                    <div class="container mt-3">
                        <h1 class="font-weight-light">Moje smlouvy</h1>
                        <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
                        <div  v-if="isBusy" class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
                        <table-for-contracts v-else :items="myContracts"></table-for-contracts>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
import navbarTop from '@/components/navbarTop';
import navbarLeft from '@/components/navbarLeft';
import tableForContracts from '@/components/tableForContracts';
import { FulfillingBouncingCircleSpinner } from 'epic-spinners';
// Komponenta pro výpis smluv profesionálnímu prodejci. Využívá tableForContracts.
export default {
  name: 'puMyContracts',
  data () {
    return {
        alarm: {
            isAlarm: false,
            type: 'danger',
            AlarmText: ''
        },
        myContracts: null,
        isBusy: true,
    }
  },
  methods:{
        /**
         * @vuese
         * Požadavek do API na získání seznamu sluv uživatele
         */
        init(){
            let param = {
                id_user: this.$root.user.id_user,
                pass: this.$root.user.pass
            }
            this.$http.post(process.env.API_URL+'v1/sl/token', param).then(function(response){
                this.$http.get(process.env.API_URL+'v1/sl/my/'+this.$root.user.id_user+'/contracts', {
                    headers: {
                    'Authorization': 'Bearer '+ response.body.token,
                    'Accept': 'application/json'
                    }
                }).then(function(response){
                    this.myContracts = response.body;
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
        }
  },
  created: function(){
    this.$root.checkIsLogin();
    this.init();
  },
  components: {
    navbarTop,
    navbarLeft,
    tableForContracts,
    FulfillingBouncingCircleSpinner
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.puMyContracts{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>