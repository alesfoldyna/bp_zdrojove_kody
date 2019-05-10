<template>
    <div class="searchResulte container">
        <main-search-vue></main-search-vue>
        <div v-if="$root.isBusy">
          <loading-comp></loading-comp>
        </div>
        <div v-else>
          <advertising-short-card></advertising-short-card>
        </div>
    </div>
</template>

<script>
import MainSearchVue from './MainSearch.vue';
import AdvertisingShortCard from './AdvertisingShortCard.vue';
import LoadingComp from './LoadingComp.vue';
/**
 * @vuese
 * Komponenta pro zobrazování výsledků. Na základě isBusy z root signalizuje načítání, nebo výsledky pomocí komponenty AdvertisingShortCard
 */
export default {
  name: 'searchResulte',
  data () {
    return {
    }
  },
  methods: {
  },
  created(){
    this.$root.loadFindList();
    this.$root.indexOfSearchPage = 0;
  },
  components: {
    MainSearchVue,
    AdvertisingShortCard,
    LoadingComp
  }
}
</script>

<style scoped>
</style>
