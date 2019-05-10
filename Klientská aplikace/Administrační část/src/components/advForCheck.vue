<template>
<div class="advForCheck">
      <navbarTop></navbarTop>
      <div class="container-fluid h-100">
        <div class="row h-100">
          <navbarLeft></navbarLeft>
          <main class="col">
            <div class="container mt-3">
              <h1 class="font-weight-light">Inzeráty ke kontrole</h1>
              <b-alert :show="alarm.isAlarm" :variant="alarm.type">{{alarm.AlarmText}}</b-alert>
              <b-row>
                <b-col md="6" class="my-1">
                    <b-form-group label-cols-sm="3" label="Filtr" class="mb-0">
                    <b-input-group>
                        <b-form-input v-model="filter" placeholder="Vyhledat" />
                        <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''">Vyčistit</b-button>
                        </b-input-group-append>
                    </b-input-group>
                    </b-form-group>
                </b-col>

                <b-col md="6" class="my-1">
                    <b-form-group label-cols-sm="3" label="Seřadit" class="mb-0">
                    <b-input-group>
                        <b-form-select v-model="sortBy" :options="sortOptions">
                        <option slot="first" :value="null">-- none --</option>
                        </b-form-select>
                        <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
                        <option :value="false">Vzestupně</option> <option :value="true">Sestupně</option>
                        </b-form-select>
                    </b-input-group>
                    </b-form-group>
                </b-col>

                <b-col md="6" class="my-1">
                    <b-form-group label-cols-sm="3" label="Na stránku" class="mb-0">
                    <b-form-select :options="pageOptions" v-model="perPage" />
                    </b-form-group>
                </b-col>
              </b-row>
              <div class="d-flex justify-content-center"><fulfilling-bouncing-circle-spinner v-if="isBusy" class="m-3" :animation-duration="4000" :size="40" color="#33cc33"/></div>
              <div  v-if="!isBusy">
                <b-table
                    show-empty
                    stacked="md"
                    :items="items"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    @filtered="onFiltered"
                    >
                    <template slot="zverejnit" slot-scope="row">
                    <div class="row justify-content-md-center">
                        <b-button size="sm" variant="primary" v-on:click="zverejnit(row.item.id_adver)">Zveřejnit</b-button>
                    </div>
                    </template>
                </b-table>

                <b-row class="justify-content-md-center">
                    <b-col md="auto" class="my-1">
                        <b-pagination
                        :total-rows="totalRows"
                        :per-page="perPage"
                        v-model="currentPage"
                        class="my-0"
                        />
                    </b-col>
                    
                </b-row>
              </div>
            </div>
          </main>
        </div>
      </div>
  </div>
</template>

<script>
import navbarTop from '@/components/navbarTop';
import navbarLeft from '@/components/navbarLeft';
import { FulfillingBouncingCircleSpinner } from 'epic-spinners';
// Komponenta pro seznam inzerátů ke kontrole (zveřejnění)
export default {
  name: 'advForCheck',
  data () {
    return {
        alarm: {
            isAlarm: false,
            type: 'danger',
            AlarmText: ''
        },
        fields: [
          {
            key: 'id_user',
            label: 'ID uživatele',
            sortable: true
          },
          {
            key: 'jmeno',
            label: 'Jméno',
            sortable: false
          },
          {
            key: 'prijmeni',
            label: 'Příjmení',
            sortable: true,
          },
          {
            key: 'znacka',
            label: 'Značka',
            sortable: true,
          },
          {
            key: 'model',
            label: 'Model',
            sortable: true,
          },
          {
            key: 'popisek',
            label: 'Popisek',
            sortable: true,
          },
          {
          key: 'zverejnit',
          label: '',
          sortable: false,
        }
        ],
        items: [],
        currentPage: 1,
        perPage: 10,
        totalRows: 0,
        pageOptions: [10, 20, 30],
        sortBy: null,
        sortDesc: false,
        filter: null,
        modalInfo: { title: '', content: '' },
        isBusy: true
    }
  },
  methods: {
        /**
         * @vuese
         * Dataz do API pro získání seznamu inzerátů ke kontrole
         */
        init(){
          let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }
          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.get(process.env.API_URL+'v1/admin/adv/forCheck', {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.items = response.body;
                this.totalRows = response.body.length;
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
        },
        /**
         * @vuese
         * Požadavek do API na zveřejnění inzerátu
         * @arg id_adver idetifikátor inzerátu
         */
        zverejnit(id_adver){
            let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
            }
            this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.put(process.env.API_URL+'v1/admin/adv/setAsPublic/'+id_adver,null, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
                }).then(function(response){
                    this.init();
                    this.alarm.isAlarm = true;
                    this.alarm.AlarmText = 'Inzerát úspěšně zveřejněn';
                    this.alarm.type = 'success';
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
        },
        /**
         * @vuese
         * Upřesňuje počet položek v tabulce po filtraci
         */
        onFiltered(filteredItems) {
            this.totalRows = filteredItems.length
            this.currentPage = 1
        }
  },
  computed: {
    /**
    * @vuese
    * Řadí výsledky podle zadaných kritérií
    */
    sortOptions() {
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
    }
  },
  created: function(){
    this.$root.checkIsLogin();
    this.init();
  },
  components: {
    navbarTop,
    navbarLeft,
    FulfillingBouncingCircleSpinner
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div.advForCheck{
   height: -webkit-calc(100% - 40px);
 height: -moz-calc(100% - 40px);
 height: calc(100% - 40px);
}
</style>
