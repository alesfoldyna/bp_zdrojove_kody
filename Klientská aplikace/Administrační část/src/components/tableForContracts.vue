<template>
    <div class="tableForContracts">
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
          <template slot="platna_od" slot-scope="row">
                {{format.asString('dd.MM.yyyy', new Date(row.value))}}
          </template>
          <template slot="platna_do" slot-scope="row">
                <div v-if="row.value === null">Na dobu neurčitou</div>
                <div v-else>{{format.asString('dd.MM.yyyy', new Date(row.value))}}</div>
          </template>
          <template slot="platna" slot-scope="row">
                <div class="text-success" v-if="row.value">ANO</div>
                <div class="text-danger" v-else>NE</div>
          </template>
          <template slot="vypovedni_obdobi" slot-scope="row">
                <div class="text-danger" v-if="row.value">ANO</div>
                <div class="text-success" v-else>NE</div>
          </template>
          <template slot="vypovedni_lhuta" slot-scope="row">
                <div class="text-success" v-if="row.value === null">Smlouva není ve výpovědním období</div>
                <div v-else>{{format.asString('dd.MM.yyyy', new Date(row.value))}}</div>
          </template>
          
          <template v-if="editAble" slot="edit" slot-scope="row">
                <b-button size="sm" @click="row.toggleDetails" v-on:click="prepareVaule(row.item.platna_od, row.item.platna_do, row.item.platna, row.item.vypovedni_obdobi, row.item.vypovedni_lhuta)" class="mr-2">Upravit</b-button>
          </template>

          <template slot="row-details" slot-scope="row">
            <b-card bg-variant="light">
              <b-form>
                  <b-row>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Platnost od:" label-align-sm="right" label-for="platnost_od">
                              <b-form-input id="platnost_od" type="date" v-model="inputsEditContract.platnost_od" required/>
                          </b-form-group>
                      </b-col>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Platnost do:" label-align-sm="right" label-for="platnost_do">
                              <b-form-input id="platnost_do" type="date" v-model="inputsEditContract.platnost_do"/>
                              <small id="popisek" class="form-text text-muted" >Pro dobu neurčitou nechte nevyplněné</small>
                          </b-form-group>
                      </b-col>
                      <b-col>
                            <b-form-group label-cols-sm="2" label="Aktivní:" label-align-sm="right" label-for="platna">
                                <b-form-checkbox id="platna" v-model="inputsEditContract.platna">Zaškrtněte, jestli je smlouva aktivní</b-form-checkbox>
                            </b-form-group>
                      </b-col>
                  </b-row>
                  <b-row>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Výpovězená:" label-align-sm="right" label-for="vypovedni_bodobi">
                                <b-form-checkbox id="vypovedni_bodobi" v-model="inputsEditContract.vypovedni_bodobi" v-on:change="inputsEditContract.vypovedni_lhuta = null">Zaškrtněte, je-li smlouva vypovězená</b-form-checkbox>
                            </b-form-group>
                      </b-col>
                      <b-col>
                          <b-form-group label-cols-sm="2" label="Výpovědní lhůta:" label-align-sm="right" label-for="vypovedni_lhuta">
                              <b-form-input id="vypovedni_lhuta" type="date" v-model="inputsEditContract.vypovedni_lhuta" :disabled="!inputsEditContract.vypovedni_bodobi" required/>
                          </b-form-group>
                      </b-col>
                  </b-row>
                  <b-button v-on:click="editContract(row.item.id_smlouva)" block variant="primary">Upravit smlouvu</b-button>
                  <b-button v-on:click="deleteContract(row.item.id_smlouva)" block variant="danger">Odstranit smlouvu</b-button>
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
// Komponenta pro tabulku výpisu smluv
export default {
  name: 'table-for-contracts',
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
     * enable for edit
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
            key: 'platna_od',
            label: 'Nabití platnosti',
            sortable: true
          },
          {
            key: 'platna_do',
            label: 'Na dobu určitou',
            sortable: true,
          },
          {
            key: 'platna',
            label: 'Aktivní',
            sortable: true,
          },
          {
            key: 'vypovedni_obdobi',
            label: 'Výpovědní období',
            sortable: true,
          },
          {
            key: 'vypovedni_lhuta',
            label: 'Výpovědní lhůta',
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
        sortBy: 'platna_od',
        sortDesc: false,
        filter: null,
        modalInfo: { title: '', content: '' },
        inputsEditContract: {
            platnost_od: null,
            platnost_do: null,
            platna: true,
            vypovedni_bodobi: false,
            vypovedni_lhuta: null,
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
       * Požadavek do API na úpravu smlouvy
       */
      editContract(id){
        let contractParam = {
              platna_od: this.inputsEditContract.platnost_od,
              platna_do: this.inputsEditContract.platnost_do,
              platna: this.inputsEditContract.platna ? 1 : 0,
              vypovedni_obdobi: this.inputsEditContract.vypovedni_bodobi ? 1 : 0,
              vypovedni_lhuta: this.inputsEditContract.vypovedni_lhuta,
        }

        let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
          }

          this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.put(process.env.API_URL+'v1/admin/contract/edit/'+id, contractParam, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Smlouva byla úspěšně upravena';
                this.alarm.type = 'success';
                this.$emit('editContract', this.alarm);
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
                this.$emit('editContract', this.alarm);
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
                this.$emit('editContract', this.alarm);
          });
      },
      /**
       * @vuese
       * Připravuje hodnoty do formuláře pro úpravu smlouvy
       */
      prepareVaule(platna_od, platna_do, platna, vypovedni_obdobi, vypovedni_lhuta){
        this.inputsEditContract.platnost_od = platna_od;
        this.inputsEditContract.platnost_do = platna_do;
        this.inputsEditContract.platna = platna;
        this.inputsEditContract.vypovedni_bodobi = vypovedni_obdobi;
        this.inputsEditContract.vypovedni_lhuta = vypovedni_lhuta;
      },
      /**
       * @vuese
       * Požadavek do API na smazání smlouvy
       */
      deleteContract(id){
        let param = {
              id_user: this.$root.user.id_user,
              pass: this.$root.user.pass
        }

        this.$http.post(process.env.API_URL+'v1/admin/token', param).then(function(response){
              this.$http.delete(process.env.API_URL+'v1/admin/contract/delete/'+id, {
                headers: {
                'Authorization': 'Bearer '+ response.body.token,
                'Accept': 'application/json'
                }
            }).then(function(response){
                this.alarm.isAlarm = true;
                this.alarm.AlarmText = 'Smlouva byla úspěšně odstraněna';
                this.alarm.type = 'warning';
                this.$emit('editContract', this.alarm);
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
                this.$emit('editContract', this.alarm);
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
                this.$emit('editContract', this.alarm);
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
