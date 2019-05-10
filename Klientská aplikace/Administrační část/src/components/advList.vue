<template>
<div class="advList">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
              <h1 class="font-weight-light">Přehled mých inzerátů</h1>
              <table-of-adv-user class="mt-3"></table-of-adv-user>
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
// Komponenta pro zobrazení seznamu inzerátů přihlášeného uživatele. K zobrazení využívá komponentu tableOfAdvUser.
export default {
  name: 'advList',
  props:{
    /**
     * ID user for select advertising
     * @default null
     * @type {Number}
     */
    id_user: {
      type: Number,
      default: null,
    },
  },
  data () {
    return {
    }
  },
  created: function(){
    this.$root.checkIsLogin();
    if(this.$route.params.isAlarm){
            this.alarm.AlarmText = this.$route.params.AlarmText;
            this.alarm.type = this.$route.params.alarmStyle;
            this.alarm.isAlarm = this.$route.params.isAlarm;
        }
  },
  components: {
    navbarTop,
    navbarLeft,
    tableOfAdvUser
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.advList{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>
