<template>
    <nav class="navbar navbar-dark bg-dark flex-md-nowrap p-0 shadow ">
      <span class="navbar-brand col-sm-3 col-md-2 mr-0 w-25">ADMIN</span>
      <span v-if="$root.user.user_type === 'ADMIN'" class="navbar-brand w-100 text-center">Administrátor</span>
      <span v-else-if="$root.user.user_type === 'PROFI'" class="navbar-brand w-100 text-center">Profesionální prodejce</span>
      <span v-else-if="$root.user.user_type === 'NOTPROFI'" class="navbar-brand w-100 text-center">Soukromý prodejce</span>
      <ul class="navbar-nav px-3">
        
      </ul>
      <ul class="navbar-nav px-3 w-25">
        <div class="d-flex flex-row-reverse">
          <li class="nav-item text-nowrap">
            <a v-if="!singIn" class="nav-link" v-on:click.prevent="backToSearch">Zpět na vyhledávání</a>
            <a v-else class="nav-link" v-on:click.prevent="logOut">Odhlásit se</a>
          </li>
          <li class="nav-item text-nowrap mr-3">
            <a class="nav-link" target="_blank" href="https://doc-admin.foldynatulbp.cloud">Dokumentace</a>
          </li>
        </div>
      </ul>
    </nav>

</template>

<script>
// Komponenta pro horní menu
export default {
  name: 'navbarTop',
  data () {
    return {
      singIn: false
    }
  },
  methods: {
    /**
     * @vuese
     * Přesměrování na vyhledávací část
     */
    backToSearch(){
      window.location.href = process.env.WWW_URL;
    },
    /**
     * @vuese
     * Vynulování uživatele (odhlášení)
     */
    logOut(){
      this.$root.user = {};
      this.$router.push({ name: 'Login' });
    }
  },
  created: function(){
    if(!(Object.keys(this.$root.user).length === 0 && this.$root.user.constructor === Object)) this.singIn = true;
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
 a{
   cursor: pointer;
 }
</style>
