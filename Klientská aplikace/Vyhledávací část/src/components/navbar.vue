<template>
      <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
        <router-link to="/" class="navbar-brand">Foldyna TUL BP</router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbarNavDropdown" class="navbar-collapse collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link to="/" class="nav-item nav-link">Vyhledávání</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/about" class="nav-item nav-link">O projektu</router-link>
                </li>
            </ul>


            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-item nav-link" target="_blank" href="https://doc-www.foldynatulbp.cloud">Dokumentace</a>
              </li>
              <li class="nav-item">
                <a v-on:click.prevent="onAdmin" class="nav-item nav-link">Přihlásit se</a>
              </li>
            </ul>
        </div>
    </nav>
</template>

<script>
// Komponenta pro horní menu.
export default {
  name: 'navbar',
  data () {
    return {
      urlAdmin: null,
    }
  },
  methods: {
    /**
     * @vuese
     * Přesměruje na adresu ADMIN
     */
    onAdmin(){
      window.location.href = process.env.ADMIN_URL;
    }
  },
  created: function(){
    this.urlAdmin = process.env.ADMIN_URL;
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
 a{
   cursor: pointer;
 }
</style>
