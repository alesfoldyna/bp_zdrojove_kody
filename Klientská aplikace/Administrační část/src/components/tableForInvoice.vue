<template>
    <div class="tableForInvoice">
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
          <template slot="vystavena" slot-scope="row">
                {{format.asString('dd.MM.yyyy', new Date(row.value))}}
          </template>
          <template slot="splatna" slot-scope="row">
                {{format.asString('dd.MM.yyyy', new Date(row.value))}}
          </template>
          <template slot="obdobi_od" slot-scope="row">
                {{format.asString('dd.MM.yyyy', new Date(row.value))}}
          </template>
          <template slot="obdobi_do" slot-scope="row">
                {{format.asString('dd.MM.yyyy', new Date(row.value))}}
          </template>
          <template slot="zaplacena" slot-scope="row">
                <div class="text-success" v-if="row.value">ANO</div>
                <div class="text-danger" v-else>NE</div>
          </template>
          
          <template v-if="editAble" slot="edit" slot-scope="row">
                <b-button size="sm" @click="row.toggleDetails" v-on:click="prepareVaule(row.item.vystavena, row.item.splatna, row.item.obdobi_od, row.item.obdobi_do, row.item.castka_float, row.item.variabilni_symbol,row.item.zaplacena)" class="mr-2">Upravit</b-button>
          </template>

          <template slot="row-details" slot-scope="row">
            <b-card bg-variant="light">
              <b-form>
                  <b-row>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Datum vystavení:" label-align-sm="right" label-for="datum_vystaveni">
                              <b-form-input id="datum_vystaveni" type="date" v-model="inputsEditInvoice.vystavena" required/>
                          </b-form-group>
                      </b-col>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Splatnost:" label-align-sm="right" label-for="splatnost">
                              <b-form-input id="splatnost" type="date" v-model="inputsEditInvoice.splatna" required/>
                          </b-form-group>
                      </b-col>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Období od:" label-align-sm="right" label-for="obdobi_od">
                              <b-form-input id="obdobi_od" type="date" v-model="inputsEditInvoice.obdobi_od" required/>
                          </b-form-group>
                      </b-col>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Období do:" label-align-sm="right" label-for="obdobi_do">
                              <b-form-input id="obdobi_do" type="date" v-model="inputsEditInvoice.obdobi_do" required/>
                          </b-form-group>
                      </b-col>
                  </b-row>
                  <b-row>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Částka:" label-align-sm="right" label-for="castka">
                              <b-form-input id="castka" type="number" v-model="inputsEditInvoice.castka" required/>
                          </b-form-group>
                      </b-col>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="VS:" label-align-sm="right" label-for="vs">
                              <b-form-input id="vs" type="number" v-model="inputsEditInvoice.variabilni_symbol" required/>
                          </b-form-group>
                      </b-col>
                  </b-row>
                  <b-form-group label-cols-sm="2" label="Uhrazena:" label-align-sm="right" label-for="uhrazena">
                      <b-form-checkbox id="uhrazena" v-model="inputsEditInvoice.zaplacena">Zaškrtněte, jestli je faktura již uhrazená</b-form-checkbox>
                  </b-form-group>
                  <b-button v-on:click="editInvoice(row.item.id_faktura)" block variant="primary">Upravit fakturu</b-button>
                  <b-button v-on:click="deleteInvoice(row.item.id_faktura)" block variant="danger">Odstranit fakturu</b-button>
              </b-form>
          </b-card>
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
</template>

<script>
// Komponta tabulky pro seznam faktur
export default {
  name: 'table-for-invoice',
  props:{
    /**
     * ID user for select advertising
     * @default []
     * @type {Array}
     */
     items: {
      type: Array,
      default: [],
    },
    /**
     * able for edit
     * @default false
     * @type {Boolean}
     */
     editAble: {
      type: Boolean,
      default: false,
    },
  },
  data () {
    return {
        alarm: {
          isAlarm: false,
          type: 'danger',
          AlarmText: ''
        },
        fields: [
          {
            key: 'vystavena',
            label: 'Datum vystavení',
            sortable: true
          },
          {
            key: 'splatna',
            label: 'Splatnost',
            sortable: true,
          },
          {
            key: 'obdobi_od',
            label: 'Za období od',
            sortable: true,
          },
          {
            key: 'obdobi_do',
            label: 'Za období do',
            sortable: true,
          },
          {
            key: 'castka',
            label: 'Částka',
            sortable: true,
          },
          {
            key: 'variabilni_symbol',
            label: 'VS',
            sortable: true,
          },
          {
          key: 'zaplacena',
          label: 'Uhrazená',
          sortable: true,
          },
          this.editAble ? {
          key: 'edit',
          label: '',
          sortable: false,
          } : {}
        ],
        format: require('date-format'),
        currentPage: 1,
        perPage: 10,
        totalRows: 0,
        pageOptions: [10, 20, 30],
        sortBy: 'vystavena',
        sortDesc: false,
        filter: null,
        modalInfo: { title: '', content: '' },
        inputsEditInvoice: {
            vystavena: null,
            splatna: null,
            obdobi_od: null,
            obdobi_do: null,
            zaplacena: false,
            castka: null,
            variabilni_symbol: null
        },
    }
  },
  methods: {
      /**
      * @vuese
      * Upřesňuje počet položek v tabulce po filtraci
      */
      onFiltered(filteredItems) {
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      /**
      * @vuese
      * Požadavek do API na úpravu faktury
      */
      editInvoice(id){
        let invoiceParam = {
              vystavena: this.inputsEditInvoice.vystavena,
              splatna: this.inputsEditInvoice.splatna,
              obdobi_od: this.inputsEditInvoice.obdobi_od,
              obdobi_do: this.inputsEditInvoice.obdobi_do,
              zaplacena: this.inputsEditInvoice.zaplacena ? 1 : 0,
              castka: parseFloat(this.inputsEditInvoice.castka),
              variabilni_symbol: parseInt(this.inputsEditInvoice.variabilni_symbol)
        }

        let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }

          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.put(process.env.API_URL+'v1/admin/invoice/edit/'+id, invoiceParam, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Faktura byla úspěšně Upravena';
                this.alarm.type = 'success';
                this.$emit('editInvoice', this.alarm);
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
                this.$emit('editInvoice', this.alarm);
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
                this.$emit('editInvoice', this.alarm);
          });
      },
      /**
       * @vuese
       * Připravuje hodnoty do formuláře pro úpravu faktury
       */
      prepareVaule(vystavena, splatna, obdobi_od, obdobi_do, castka, variabilni_symbol, zaplacena){
        this.inputsEditInvoice.vystavena = vystavena;
        this.inputsEditInvoice.splatna = splatna;
        this.inputsEditInvoice.obdobi_od = obdobi_od;
        this.inputsEditInvoice.obdobi_do = obdobi_do;
        this.inputsEditInvoice.zaplacena = zaplacena;
        this.inputsEditInvoice.castka = castka;
        this.inputsEditInvoice.variabilni_symbol = variabilni_symbol;
      },
      /**
      * @vuese
      * Požadavek do API na smazání faktury
      * @arg id identifikátor faktury
      */
      deleteInvoice(id){
        let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
        }

        this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.delete(process.env.API_URL+'v1/admin/invoice/delete/'+id, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Faktura byla úspěšně odstraněna';
                this.alarm.type = 'warning';
                this.$emit('editInvoice', this.alarm);
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
                this.$emit('editInvoice', this.alarm);
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
                this.$emit('editInvoice', this.alarm);
          });
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
  },
  components: {

  }
}
</script>

<style scoped>

</style>
