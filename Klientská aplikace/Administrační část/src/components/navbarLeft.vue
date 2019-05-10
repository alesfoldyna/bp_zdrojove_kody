<template>
        <aside class="menu bg-dark shadow">
            <nav class="navbar navbar-expand navbar-dark bg-dark flex-md-column flex-row align-items-start">
                <div class="collapse navbar-collapse">
                    <ul class="flex-md-column flex-row navbar-nav w-100 justify-content-between">
                        <li class="nav-item ">
                            <router-link class="nav-link" :class="activeClass[0].active ? 'active' : ''" to="/">Uživatelský přehled</router-link>
                        </li>
                        <li class="nav-item dropright">
                            <b-nav-item-dropdown text="Správa inzerátů" :class="(activeClass[1].active || activeClass[2].active || activeClass[5].active)? 'active' : '' "  right>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/advList">Přehled mých inzerátů</router-link></b-dropdown-item>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/addAdvertising">Přidat inzerátatů</router-link></b-dropdown-item>
                                <b-dropdown-item v-show="$root.user.user_type === 'ADMIN'"><router-link class="dropdown-item" to="/advForCheck">Inzeráty ke kontrole</router-link></b-dropdown-item>
                            </b-nav-item-dropdown>
                        </li>
                        <li class="nav-item dropright" v-show="$root.user.user_type === 'ADMIN'">
                            <b-nav-item-dropdown text="Správa uživatelů" :class="(activeClass[3].active || activeClass[4].active)? 'active' : '' "  right>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/usersList">Seznam uživatelů</router-link></b-dropdown-item>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/addUser">Přidat uživatele</router-link></b-dropdown-item>
                            </b-nav-item-dropdown>
                        </li>
                        <li class="nav-item dropright" v-show="$root.user.user_type === 'ADMIN'">
                            <b-nav-item-dropdown text="Správa databáze" :class="(activeClass[6].active || activeClass[7].active || activeClass[8].active || activeClass[11].active)? 'active' : '' "  right>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/carBrandManagement">Značka</router-link></b-dropdown-item>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/modelManagementList">Model</router-link></b-dropdown-item>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/gearManagement">Výbava</router-link></b-dropdown-item>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/conditionsManagementList">Všeobecné podmínky</router-link></b-dropdown-item>
                            </b-nav-item-dropdown>
                        </li>
                        <li class="nav-item dropright" v-show="$root.user.user_type === 'PROFI'">
                            <b-nav-item-dropdown text="Můj přehled" :class="(activeClass[9].active || activeClass[10].active)? 'active' : '' "  right>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/puMyInvoice">Moje faktury</router-link></b-dropdown-item>
                                <b-dropdown-item ><router-link class="dropdown-item" to="/puMyContracts">Moje smlouvy</router-link></b-dropdown-item>
                            </b-nav-item-dropdown>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>
</template>

<script>
// Komponta pro levé menu
export default {
  name: 'navbarLeft',
  data () {
    return {
        activeClass: [
            {name: 'AdminHome', active: false},
            {name: 'addAdvertising', active: false},
            {name: 'advList', active: false},
            {name: 'usersList', active: false},
            {name: 'addUser', active: false},
            {name: 'advForCheck', active: false},
            {name: 'gearManagement', active: false},
            {name: 'carBrandManagement', active: false},
            {name: 'modelManagementList', active: false},
            {name: 'puMyInvoice', active: false},
            {name: 'puMyContracts', active: false},
            {name: 'conditionsManagementList', active: false},
        ]
    }
  },
  methods: {
      /**
       * @vuese
       * Zjišťování aktivní cesty
       */
      checkActiveRoute(){
        for (let i = 0; i< this.activeClass.length; i++) {
            if(this.activeClass[i].name === this.$route.name){
                this.activeClass[i].active = true;
            } else this.activeClass[i].active = false;
        }
      }
  },
  created: function(){
      this.checkActiveRoute();
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
aside.menu {
    min-width: 200px;
}
</style>
