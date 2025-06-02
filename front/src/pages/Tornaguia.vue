<template>
  <q-page>
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-12 col-sm-4">
            <q-input v-if="store.permissions.includes('tornaguia read')" outlined dense type="date" v-model="fecha1"
                     label="Fecha Desde"/>
          </div>
          <div class="col-12 col-sm-4">
            <q-input v-if="store.permissions.includes('tornaguia read')" outlined dense type="date" v-model="fecha2"
                     label="Fecha Hasta"/>
          </div>
          <div class="col-12 col-sm-4 flex flex-center">
            <q-btn v-if="store.permissions.includes('tornaguia read')" icon="search" color="primary" label="Buscar"
                   @click="buscar" :loading="loading"/>
          </div>
        </div>
      </q-card-section>
    </q-card>
    <q-table :rows="tornaguias" :columns="tornaguiaColums" :filter="search" :rows-per-page-options="[0]" dense>
      <template v-slot:top-right>
        <q-toolbar>
          <q-btn v-if="store.permissions.includes('tornaguia read')" color="green"
                 :label="$q.screen.lt.md?'':'Descargar excel'" icon="download" @click="tornaguiaDownload"
                 :loading="loading" no-caps/>
          <q-btn v-if="store.permissions.includes('tornaguia create')" color="primary"
                 :label="$q.screen.lt.md?'':'Crear tornagia'" icon="add_circle_outline" @click="tornaguiaClick"
                 :loading="loading" no-caps/>
          <q-input v-model="search" outlined dense placeholder="Buscar..."/>
        </q-toolbar>
      </template>
      <template v-slot:body-cell-tipoMaterial="props">
        <q-td :props="props" auto-width>
          <q-badge dense text-color="white" :color="props.row.tipoMaterial=='Embolsado'?'green':'red'">
            {{ props.row.tipoMaterial }}
          </q-badge>
        </q-td>
      </template>
      <template v-slot:body-cell-option="props">
        <q-td :props="props" auto-width>
          <q-btn-dropdown color="primary" label="Opciones" no-caps dense size="10px">
            <q-list>
              <q-item v-if="store.permissions.includes('tornaguia update')" clickable v-close-popup
                      @click="ver(props.row)">
                <q-item-section avatar>
                  <q-icon name="o_edit"/>
                </q-item-section>
                <q-item-section>Modificar</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('tornaguia read')" clickable v-close-popup
                      @click="print(props.row,'todo')">
                <q-item-section avatar>
                  <q-icon name="o_print"/>
                </q-item-section>
                <q-item-section>Imprimir Todo</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('tornaguia read')" clickable v-close-popup
                      @click="print(props.row,'nada')">
                <q-item-section avatar>
                  <q-icon name="o_print"/>
                </q-item-section>
                <q-item-section>Imprimir sin nada</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('tornaguia read')" clickable v-close-popup
                      @click="print(props.row,'fondo')">
                <q-item-section avatar>
                  <q-icon name="o_print"/>
                </q-item-section>
                <q-item-section>Imprimir con fondo</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('tornaguia read')" clickable v-close-popup
                      :to="'/show/'+props.row.id">
                <q-item-section avatar>
                  <q-icon name="o_visibility"/>
                </q-item-section>
                <q-item-section>Ver</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('tornaguia read')" clickable v-close-popup
                      @click="printPage(props.row)">
                <q-item-section avatar>
                  <q-icon name="o_print"/>
                </q-item-section>
                <q-item-section>Imprimir hoja</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>
    </q-table>
    <!--    <pre>{{tornaguias}}</pre>-->
    <!--    <q-dialog v-model="showAddTornaguiaDialog" full-width >-->
    <!--      <q-card>-->
    <!--        <q-card-section class="row items-center q-pb-none">-->
    <!--          <div class="text-h6">{{ tornaguiaCrear?'Generacion de':'Modificar'  }} Tornaguia o Cartaporte</div>-->
    <!--        </q-card-section>-->
    <!--        <q-card-section class="q-pt-none">-->
    <!--          <q-form @submit.prevent="tornaguiaCreate">-->
    <!--            <q-card>-->
    <!--              <q-card-section class="q-pa-xs">-->
    <!--                <div class="row">-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-input  v-model="tornaguia.fecha" type="date" :label="`Fecha: ${tornaguia.fecha} `" outlined dense />-->
    <!--                  </div>-->
    <!--&lt;!&ndash;                  <div class="col-12 col-sm-4">&ndash;&gt;-->
    <!--&lt;!&ndash;                    <q-input  v-model="tornaguia.hora" type="time" :label="`Hora: ${tornaguia.hora} `" outlined dense />&ndash;&gt;-->
    <!--&lt;!&ndash;                  </div>&ndash;&gt;-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-input  v-model="tornaguia.numero" type="number" label="Número Asignado:" outlined dense :rules="[val => val > 0 || 'Debe ser mayor a 0']" />-->
    <!--                  </div>-->
    <!--                </div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="bg-primary text-white q-pa-xs">-->
    <!--                <div class="text-subtitle2">PROPIETARIO DEL MINERAL</div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="q-pa-xs">-->
    <!--                <div class="row">-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-select  v-model="tornaguia.empresas" :rules="[ (val, rules) => !!val || 'Porfavor registrar dato' ]" required :options="empresas" label="Empresa Destiono:" outlined dense />-->
    <!--                  </div>-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-select  v-model="tornaguia.contratistas"  :rules="[val=>!!val||'Porfavor registrar dato']" required :options="contratistas" label="Contratista:" outlined dense />-->
    <!--                  </div>-->
    <!--                </div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="bg-primary text-white q-pa-xs">-->
    <!--                <div class="text-subtitle2">PROCEDENCIA</div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="q-pa-xs">-->
    <!--                <div class="row">-->
    <!--                  <div class="col-12 col-sm-4">-->
    <!--                    <q-input  v-model="tornaguia.yacimiento" label="Yacimiento:" outlined dense :rules="[val=>!!val||'Porfavor registrar dato']" />-->
    <!--                  </div>-->
    <!--                  <div class="col-12 col-sm-4">-->
    <!--                    <q-input  v-model="tornaguia.tranca" label="Tranca:" outlined dense :rules="[val=>!!val||'Porfavor registrar dato']" />-->
    <!--                  </div>-->
    <!--                  <div class="col-12 col-sm-4">-->
    <!--                    <q-input  v-model="tornaguia.cuadrilla" label="Cuadrilla:" outlined dense :rules="[val=>!!val||'Porfavor registrar dato']" />-->
    <!--                  </div>-->
    <!--                </div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="bg-primary text-white q-pa-xs">-->
    <!--                <div class="text-subtitle2">MEDIO DE TRANSPORTE</div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="q-pa-xs">-->
    <!--                <div class="row">-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-select  v-model="tornaguia.transportes" :rules="[val=>!!val||'Porfavor registrar dato']" required :options="transportes" label="Transporte:" outlined dense />-->
    <!--                  </div>-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-select  v-model="tornaguia.drivers" :rules="[val=>!!val||'Porfavor registrar dato']"  required :options="drivers" label="Conductor:" outlined dense />-->
    <!--                  </div>-->
    <!--                </div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="bg-primary text-white q-pa-xs">-->
    <!--                <div class="text-subtitle2">TIPO DE MATERIAL</div>-->
    <!--              </q-card-section>-->
    <!--              <q-card-section class="q-pa-xs">-->
    <!--                <div class="row">-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <div class="text-bold">Tipo de Material:</div>-->
    <!--                    <q-radio  v-model="tornaguia.tipoMaterial" val="Mineral" label="Mineral" :rules="[val=>!!val||'Porfavor registrar dato']" />-->
    <!--                    <q-radio  v-model="tornaguia.tipoMaterial" val="Embolsado" label="Embolsado" :rules="[val=>!!val||'Porfavor registrar dato']" />-->
    <!--                  </div>-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <div class="text-bold">Minerales:</div>-->
    <!--                    <q-checkbox  v-model="tornaguia.mineralesSel" val="Sn" label="Sn" />-->
    <!--                    <q-checkbox  v-model="tornaguia.mineralesSel" val="Ag" label="Ag" />-->
    <!--                    <q-checkbox  v-model="tornaguia.mineralesSel" val="Pb" label="Pb" />-->
    <!--                    <q-checkbox  v-model="tornaguia.mineralesSel" val="Zn" label="Zn" />-->
    <!--                    <q-checkbox  v-model="tornaguia.mineralesSel" val="Otros" label="Otros" />-->
    <!--&lt;!&ndash;                    <q-input  v-model="tornaguia.minerales" disable label="Minerales:" outlined dense />&ndash;&gt;-->
    <!--                  </div>-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-input  v-model="tornaguia.peso" v-if="tornaguia.tipoMaterial=='Mineral'" type="number" label="Peso en Tn.:" outlined dense />-->
    <!--                  </div>-->
    <!--                  <div class="col-12 col-sm-6">-->
    <!--                    <q-input  v-model="tornaguia.sacos" v-if="tornaguia.tipoMaterial=='Embolsado'" type="number" label="Cantidad de Sacos:" outlined dense />-->
    <!--                  </div>-->
    <!--                </div>-->
    <!--              </q-card-section>-->
    <!--            </q-card>-->
    <!--            <q-btn :loading="loading"  type="submit" color="primary" icon="add_circle_outline" :label="tornaguiaCrear?'Guardar':'Modificar'" class="full-width" />-->
    <!--          </q-form>-->
    <!--        </q-card-section>-->
    <!--      </q-card>-->
    <!--    </q-dialog>-->
    <!--    <q-dialog v-model="showUpdateTornaguiaDialog" >-->
    <!--      <q-card class="full-width">-->
    <!--        <q-card-section class="row items-center">-->
    <!--          <div class="text-h6">Generacion de Tornaguia o Cartaporte</div>-->
    <!--        </q-card-section>-->
    <!--        <q-card-section>-->
    <!--          <q-form @submit.prevent="tornaguiaUpdate">-->
    <!--            <q-input v-model="tornaguia.nombre" hint="" required outlined label="Nombre" />-->
    <!--            <q-select v-model="tornaguia.ciudad" hint="" required outlined label="Ciudad" :options="['ORURO','LA PAZ','COCHABAMBA','SANTA CRUZ','POTOSI','TARIJA','CHUQUISACA','BENI','PANDO']" />-->
    <!--            <q-input v-model="tornaguia.telefono" hint="" required outlined label="Telefono" />-->
    <!--            <q-input v-model="tornaguia.nit" hint="" required outlined label="NIT" />-->
    <!--            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />-->
    <!--          </q-form>-->
    <!--        </q-card-section>-->
    <!--      </q-card>-->
    <!--    </q-dialog>-->

    <q-dialog v-model="showAddTornaguiaDialog" full-width>
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">{{ tornaguiaCrear ? 'Generacion de' : 'Modificar' }} Tornaguia o Cartaporte</div>
        </q-card-section>

        <q-card>
          <q-card-section class="q-pa-xs">
            <q-form @submit.prevent="submitForm">
              <div class="row q-col-gutter-sm">
                <div class="col-12 col-md-2">
                  <q-input v-model="tornaguia.fecha" type="date" label="Fecha" outlined dense/>
                </div>
                <div class="col-12 col-md-2">
                  <q-input v-model="tornaguia.numero" label="Número" outlined dense />
                </div>

                <div class="col-12 col-md-2">
                  <q-input v-model="tornaguia.hora" type="time" label="Hora" outlined dense/>
                </div>

                <div class="col-12 col-md-2">
                  <q-select v-model="tornaguia.departamento"
                            :options="['Oruro', 'La Paz', 'Cochabamba', 'Santa Cruz', 'Potosi', 'Tarija', 'Chuquisaca', 'Beni', 'Pando']"
                            label="Departamento" outlined dense/>
                </div>

                <div class="col-12 col-md-2">
                  <q-input v-model="tornaguia.centroMinero" label="Centro Minero" outlined dense/>
                </div>

                <div class="col-12 col-md-2">
                  <q-input v-model="tornaguia.yacimiento" label="Yacimiento" outlined dense/>
                </div>

                <div class="col-12 col-md-2">
                  <q-input v-model="tornaguia.trancaSalida" label="Tranca Salida" outlined dense/>
                </div>

                <div class="col-12 col-md-3">
<!--                  <q-select v-model="tornaguia.empresas" :options="empresas" label="Empresa Destino" outlined dense/>-->
                  <q-select v-model="tornaguia.empresa_id"
                            :options="empresas"
                            option-label="label"
                            option-value="value"
                            map-options
                            emit-value
                            label="Empresa Destino"
                            outlined dense />
                </div>

                <div class="col-12 col-md-3">
<!--                  <q-select v-model="tornaguia.contratistas" :options="contratistas" label="Contratista" outlined-->
<!--                            dense/>-->
                  <q-select v-model="tornaguia.contratista_id"
                            :options="contratistas"
                            option-label="label"
                            option-value="value"
                            map-options
                            emit-value
                            label="Contratista"
                            outlined dense />
                </div>

                <div class="col-12 col-md-3">
                  <q-input v-model="tornaguia.nit" label="NIT" outlined dense/>
                </div>

                <div class="col-12 col-md-3">
                  <q-input v-model="tornaguia.comprador" label="Comprador" outlined dense/>
                </div>

                <div class="col-12 col-md-3">
                  <q-input v-model="tornaguia.destino" label="Destino" outlined dense/>
                </div>

                <div class="col-12 col-md-3">
<!--                  <q-select v-model="tornaguia.transportes" :options="transportes" label="Transporte" outlined dense/>-->
                  <q-select v-model="tornaguia.transporte_id"
                            :options="transportes"
                            option-label="label"
                            option-value="value"
                            map-options
                            emit-value
                            label="Transporte"
                            outlined dense />
                </div>

                <div class="col-12 col-md-3">
<!--                  <q-select v-model="tornaguia.drivers" :options="drivers" label="Conductor" outlined dense/>-->
                  <q-select v-model="tornaguia.driver_id"
                            :options="drivers"
                            option-label="label"
                            option-value="value"
                            map-options
                            emit-value
                            label="Conductor"
                            outlined dense />
                </div>

                <div class="col-12 col-md-3">
                  <q-input v-model="tornaguia.cuadrilla" label="Cuadrilla" outlined dense/>
                </div>

                <div class="col-12 col-md-3">
                  <q-radio v-model="tornaguia.tipoMaterial" val="Mineral" label="Mineral"/>
                  <q-radio v-model="tornaguia.tipoMaterial" val="Embolsado" label="Embolsado"/>
                </div>

                <div class="col-12 col-md-3">
                  <q-checkbox v-for="mineral in ['Sn', 'Ag', 'Pb', 'Zn', 'Otros']" :key="mineral"
                              v-model="tornaguia.mineralesSel" :val="mineral" :label="mineral"/>
                </div>

                <div class="col-12 col-md-3" v-if="tornaguia.tipoMaterial === 'Mineral'">
                  <q-input v-model="tornaguia.peso" type="number" label="Peso (Toneladas)" outlined dense/>
                </div>

                <div class="col-12 col-md-3" v-if="tornaguia.tipoMaterial === 'Embolsado'">
                  <q-input v-model="tornaguia.sacos" type="number" label="Cantidad de Sacos" outlined dense/>
                </div>

                <div class="col-12 col-md-3">
                  <q-input v-model="tornaguia.cantidad" type="number" label="Cantidad" outlined dense/>
                </div>

                <div class="col-12 col-md-3">
                  <q-input v-model="tornaguia.lote" label="N° de Lote" outlined dense/>
                </div>
                <div class="col-12 col-md-3">
                  <q-input v-model="tornaguia.broza" label="Broza" outlined dense/>
                </div>
              </div>
              <q-card-actions align="right">
                <q-btn label="Cancelar" flat v-close-popup/>
                <q-btn :loading="loading" type="submit" color="primary"
                       icon="add_circle_outline" :label="tornaguiaCrear ? 'Guardar' : 'Modificar'"/>
              </q-card-actions>
            </q-form>
          </q-card-section>
        </q-card>
      </q-card>
    </q-dialog>

    <div id="myelement" class="hidden"></div>
  </q-page>
</template>

<script>
import {date} from "quasar";
import {useCounterStore} from "stores/example-store";
import {Printd} from 'printd'
import QRCode from 'qrcode'
import xlsx from "json-as-xlsx"
import {jsPDF} from 'jspdf'
import moment from "moment";

export default {
  name: `Tornaguia`,
  data() {
    return {
      store: useCounterStore(),
      roles: [
        'INSCRIPCION',
        'ACREDITACION',
        'REFRIGERIO',
        'ADMINISTRADOR',
      ],
      shape: [],
      showAddTornaguiaDialog: false,
      showUpdateTornaguiaDialog: false,
      search: '',
      tornaguias: [],
      tornaguia: {
        fecha: date.formatDate(new Date(), 'YYYY-MM-DD'),
        mineralesSel: [],
      },
      fecha1: moment().startOf('week').format('YYYY-MM-DD'),
      fecha2: moment().endOf('week').format('YYYY-MM-DD'),
      loading: false,
      tornaguiaCrear: true,
      empresas: [],
      contratistas: [],
      transportes: [],
      drivers: [],
      tornaguiaColums: [
        {name: 'option', field: 'option', label: 'Opciones', align: 'left', sortable: true},
        {name: 'numero', field: 'numero', label: 'No.', align: 'left', sortable: true},
        {
          name: 'fecha',
          field: row => row.fecha + ' ' + (row.hora ? row.hora.substring(0, 5) : ''),
          label: 'Fecha',
          align: 'left',
          sortable: true
        },
        {name: 'empresa', label: 'Empresa destino', field: row => row.empresa?.nombre, align: 'left', sortable: true},
        {
          name: 'contratista',
          label: 'Contratista',
          field: row => row.contratista?.nombre,
          align: 'left',
          sortable: true
        },
        {name: 'minerales', label: 'Minerales', field: 'minerales', align: 'left', sortable: true},
        {name: 'tipoMaterial', label: 'Tipo Material', field: 'tipoMaterial', align: 'left', sortable: true},
        {name: 'transporte', label: 'Transporte', field: row => row.transporte?.placa, align: 'left', sortable: true},
        {name: 'conductor', label: 'Conductor', field: row => row.driver?.name, align: 'left', sortable: true},
        {name: 'user', label: 'Usuario', field: row => row.user?.name, align: 'left', sortable: true},
      ]
    }
  },
  mounted() {
    // this.fecha1 = this.initWeek()
    // this.initWeek()
    // this.fecha2 = this.finSemana()
    this.tornaguiasGet()
    // this.$api.get('empresa').then((response) => {
    //   response.data.forEach((empresa) => {
    //     this.empresas.push({label:empresa.nombre, value:empresa.id})
    //   })
    // });
    // this.$api.get('contratista').then((response) => {
    //   response.data.forEach((contratista) => {
    //     this.contratistas.push({label:contratista.nombre, value:contratista.id})
    //   })
    // });
    // this.$api.get('transporte').then((response) => {
    //   response.data.forEach((transporte) => {
    //     this.transportes.push({label:transporte.placa, value:transporte.id})
    //   })
    // });
    // this.$api.get('driver').then((response) => {
    //   response.data.forEach((drive) => {
    //     this.drivers.push({label:drive.name, value:drive.id})
    //   })
    // });
    this.$api.get('datos').then((response) => {
      // this.empresas = response.data.empresas.map(empresa => ({label: empresa.nombre, value: empresa.id}))
      // this.contratistas = response.data.contratistas.map(contratista => ({
      //   label: contratista.nombre,
      //   value: contratista.id
      // }))
      // this.transportes = response.data.transportes.map(transporte => ({label: transporte.placa, value: transporte.id}))
      // this.drivers = response.data.drivers.map(driver => ({label: driver.name, value: driver.id}))
      this.empresas = response.data.empresas.map(e => ({ label: e.nombre, value: e.id }))
      this.contratistas = response.data.contratistas.map(c => ({ label: c.nombre, value: c.id }))
      this.transportes = response.data.transportes.map(t => ({ label: t.placa, value: t.id }))
      this.drivers = response.data.drivers.map(d => ({ label: d.name, value: d.id }))
    });
  },
  methods: {
    submitForm() {
      if (this.tornaguiaCrear) {
        this.tornaguiaCreate();
      } else {
        this.tornaguiaUpdate();
      }
    },
    // finSemana(){
    //   let date = new Date();
    //   let day = date.getDay();
    //   let diff = date.getDate() - day + (day == 0 ? -6:1);
    //   let lunes = new Date(date.setDate(diff));
    //   return date.formatDate(lunes, 'YYYY-MM-DD')
    // },
    initWeek() {
      let dayWeek = date.formatDate(new Date(), 'E')
      let dayMonth = date.formatDate(new Date(), 'D')
      let day = dayMonth - dayWeek + (dayWeek == 0 ? -6 : 1);
      return date.formatDate(new Date(new Date().setDate(day)), 'YYYY-MM-DD')
    },
    printPage(tornaguia) {
      console.log(tornaguia)
      const doc = new jsPDF('portrait', 'cm', 'letter')
      doc.setFontSize(12)
      doc.setFont('helvetica', 'bold')
      let y = 0.5
      doc.text(tornaguia.fecha + ' ' + (tornaguia.hora ? tornaguia.hora.substring(0, 5) : ''), 2.5, 4.5 + y);
      doc.text(tornaguia.yacimiento + '', 3.5, 6.5 + y);
      // doc.text(tornaguia.cuadrilla+'', 7, 6.5+y);
      doc.text(tornaguia.tranca + '', 4, 7.3 + y);
      doc.text(tornaguia.empresa.nombre + '', 12, 6.5 + y);
      doc.text(tornaguia.contratista.nombre + '', 13, 7.3 + y);
      doc.text(tornaguia.transporte.tipo + '', 2.3, 8.7 + y);
      doc.text(tornaguia.transporte.marca + '', 6.5, 8.7 + y);
      doc.text(tornaguia.transporte.color + '', 2.3, 9.5 + y);
      doc.text(tornaguia.transporte.placa + '', 6.5, 9.5 + y);
      doc.text(tornaguia.driver.name + '', 4.5, 10.2 + y);
      doc.text(tornaguia.driver.licencia + '', 2.5, 11 + y);
      doc.text(tornaguia.tipoMaterial + '', 13.5, 8.7 + y);
      doc.text(tornaguia.minerales + '', 17, 9.3 + y);
      doc.text((tornaguia.peso == null ? '' : tornaguia.peso) + '', 13.5, 9.7 + y);
      doc.text((tornaguia.sacos == null ? '' : tornaguia.sacos) + '', 13.5, 10.4 + y);
      doc.text(tornaguia.cuadrilla + '', 13.5, 10.9 + y);
      const time = date.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss')
      var opts = {
        errorCorrectionLevel: 'H',
        type: 'image/jpeg',
        margin: 1,
      }
      QRCode.toDataURL(process.env.API_FRONT_LOCAL + 'show/' + tornaguia.id, function (err, url) {
        doc.addImage(url, 'JPEG', 17, 10.5, 4, 4)
        doc.save(`torna${time}.pdf`);
      })
    },
    print(tornaguia, tipo) {
      tornaguia.numero = tornaguia.numero.toString().padStart(6, '0')
      let fondo = ''

      if (tipo == 'fondo') {
        tipo = 'nada'
        fondo = 'fondo'
      }
      console.log(fondo)
      const d = new Printd()
      var opts = {
        errorCorrectionLevel: 'H',
        type: 'image/jpeg',
        // quality: 0.3,
        margin: 1,
        // color: {
        //   dark:"#010599FF",
        //   light:"#FFBF60FF"
        // }
      }
      QRCode.toDataURL(process.env.API_FRONT_LOCAL + 'show/' + tornaguia.id, opts).then(url => {
        document.getElementById('myelement').innerHTML = `
<style>
  .center {
    text-align: center;
  }
  .border{
    border: 1px solid #70A89F;
  }
  .color{
    color: #70A89F;
  }
  .white{
    color: #fff;
  }
  .bold{
    font-weight: bold;
  }
  .width-100{
    width: 100%;
  }
  .width-50{
    width: 50%;
  }
   .width-25{
    width: 25%;
  }

  .black{
    color: black;
  }
  .red{
    color: #ce1212;
  }
  .h1{
    font-size: 1.5rem;
  }
  .h2{
    font-size: 1.3rem;
  }
  .h3{
    font-size: 1.2rem;
  }
  .h4{
    font-size: 1rem;
  }
  .h5{
    font-size: 0.8rem;
  }
  .background{
    background-color: #E4EBF5;
  }
  .background-black{
    background-color: #639F93;
  }
  .collapse{
    border-collapse: collapse;
  }
  .hide{
    visibility: hidden;
  }
  .show{
    visibility: visible;
  }
  .right{
    text-align: right;
  }
  .absolute-position{
    position: absolute;
    top: 0px;
    z-index: -1;
    width: 700px;
  }
  .padding-right{
    padding-right: 20px;
  }
</style>
<img class="absolute-position ${fondo == 'fondo' ? 'show' : 'hide'}" src="fondo.png" alt="imagen" >
<table class="border collapse width-100 ${tipo == 'nada' ? 'hide' : 'show'}">
  <tr>
    <td class="center border"><img src="logo1.png" alt="logo1" width="50px"></td>
    <td class="center border color ">
    <div class="bold h1">Cooperativa Minera "POOPO" R.L.</div>
    <div class="h5">Personaria Juridica No. 487 - Fiche de REgistro No. 464 NIT 1023457027</div>
    <div class="h5">Cel. 72318861-71852849-72467605-71101685</div>
    <div class="h5">Poopo-Oruro-Bolivia</div>
    </td>
    <td class="center border"><img src="logo2.png" alt="logo1" width="50px"></td>
  </tr>
  <tr>
    <td class="center border" colspan="3">
      <div class="bold h2 color background">TORNAGUIA O CARTA PORTE</div>
    </td>
</tr>
</table>
<table class="width-100" >
  <tr>
    <td class="${tipo == 'nada' ? '' : 'border'} width-50">
      <small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Fecha:</small><small class="black"> ${tornaguia.fecha} </small>
    </td>
    <td class="${tipo == 'nada' ? '' : 'border'} width-50" >
      <!--div  style="display: flex; justify-content: space-between"><span class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">No:</span> <div class="red bold padding-right">${tornaguia.numero}</div></div-->
    </td>
   </tr>
   <tr>
    <td class="${tipo == 'nada' ? '' : 'border'} center white background-black ${tipo == 'nada' ? 'hide' : 'show'}">
      PROCEDENCIA
    </td>
    <td class="${tipo == 'nada' ? '' : 'border'} center white background-black ${tipo == 'nada' ? 'hide' : 'show'}">
      PROPIETARIO DEL MINERAL
    </td>
</tr>
  <tr>
    <td class="${tipo == 'nada' ? '' : 'border'} width-50">
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Departamento:</small><small class="color ${tipo == 'nada' ? 'hide' : 'show'}"> Oruro Minicipio: Poopo </small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Yacimiento:</small><small class="black"> ${tornaguia.yacimiento}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Cuadrilla:</small><small class="black"> ${tornaguia.cuadrilla}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Tranca de control:</small><small class="black"> ${tornaguia.tranca}</small></div>
    </td>
    <td class="${tipo == 'nada' ? '' : 'border'} width-50">
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Cooperativa Minera Poopo R.L.:</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Destino Empresa:</small><small class="black"> ${tornaguia.empresa.nombre}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Contratista:</small><small class="black"> ${tornaguia.contratista.nombre}</small></div>
    </td>
   </tr>
      <tr>
    <td class="${tipo == 'nada' ? '' : 'border'} center white background-black ${tipo == 'nada' ? 'hide' : 'show'}">
      MEDIO DE TRANSPORTE
    </td>
    <td class="${tipo == 'nada' ? '' : 'border'} center white background-black ${tipo == 'nada' ? 'hide' : 'show'}">
    TIPO DE MATERIAL
    </td>
</tr>
  <tr>
    <td class="${tipo == 'nada' ? '' : 'border'} width-50">
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Tipo de transporte:</small><small class="black"> ${tornaguia.transporte.tipo} </small> <small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Marca:</small><small class="black"> ${tornaguia.transporte.marca} </small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Color:</small><small class="black"> ${tornaguia.transporte.color}</small><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Placa:</small><small class="black"> ${tornaguia.transporte.placa}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Nombre del chofer:</small><small class="black"> ${tornaguia.driver.name}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Ci :</small><small class="black"> ${tornaguia.driver.licencia}</small></div>
    </td>
    <td class="${tipo == 'nada' ? '' : 'border'} width-50">
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Tipo de Material:</small><small class="black">${tornaguia.tipoMaterial}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Minerales:</small><small class="black"> ${tornaguia.minerales}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Peso en Toneladas:</small><small class="black"> ${tornaguia.peso}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">Cantidad de Sacos:</small><small class="black"> ${tornaguia.sacos}</small></div>
      <div><small class="bold h4 color ${tipo == 'nada' ? 'hide' : 'show'}">No. de Lote:</small><small class="black"> </small></div>
    </td>
   </tr>
</table>
<table class="width-100">
<tr valign="bottom">
    <td class="width-25 ${tipo == 'nada' ? '' : 'border'} center color ${tipo == 'nada' ? 'hide' : 'show'}" >
        <div class="h5">Presidente, Consejo de</div>
        <div class="h5">Administracion</div>
        <div class="h5">Sello, Nombre, C.I. y Firma</div>
     </td>
     <td class="width-25 ${tipo == 'nada' ? '' : 'border'} center color ${tipo == 'nada' ? 'hide' : 'show'}">
        <div class="h5">Empresa Receptora</div>
        <div class="h5">Sello y Firma</div>
     </td>
     <td class="width-25 ${tipo == 'nada' ? '' : 'border'} center color ${tipo == 'nada' ? 'hide' : 'show'}">
        <div class="h5">Presidente, Consejo de</div>
        <div class="h5">Vigilancia</div>
        <div class="h5">Sello, Nombre, C.I. y Firma</div>
     </td>
     <td class="width-25 ${tipo == 'nada' ? '' : 'border'} right color ">
        <img src="${url}" alt="" width="100px">
     </td>
</tr>
</table>
    `
        d.print(document.getElementById('myelement'))
      })
        .catch(err => {
          console.error(err)
        })
    },
    ver(tornaguia) {
      // console.log(tornaguia)
      this.showAddTornaguiaDialog = true
      this.tornaguiaCrear = false
      this.tornaguia = tornaguia
      this.tornaguia.transportes = {label: tornaguia.transporte.placa, value: tornaguia.transporte.id}
      this.tornaguia.empresas = {label: tornaguia.empresa.nombre, value: tornaguia.empresa.id}
      this.tornaguia.contratistas = {label: tornaguia.contratista.nombre, value: tornaguia.contratista.id}
      this.tornaguia.drivers = {label: tornaguia.driver.name, value: tornaguia.driver.id}
      if (tornaguia.minerales != null) {
        this.tornaguia.mineralesSel = tornaguia.minerales.split(",")
      } else {
        this.tornaguia.mineralesSel = []
      }
    },
    tornaguiaUpdate() {
      this.loading = true

      let materiales = ''
      this.tornaguia.mineralesSel.forEach((mineral) => {
        materiales += mineral + ','
      })
      this.tornaguia.minerales = materiales

      this.$api.put('tornaguia/' + this.tornaguia.id, this.tornaguia).then(response => {
        this.tornaguiasGet()
        this.showAddTornaguiaDialog = false
        this.tornaguia = {}
        this.loading = false
        this.$q.notify({
          color: 'positive',
          message: 'Tornaguia modificada correctamente',
          icon: 'check_circle',
          position: 'top'
        })
      }).catch(error => {
        this.loading = false
        this.$q.notify({
          color: 'negative',
          message: error.response?.data?.message || 'Error al modificar',
          icon: 'report_problem',
          position: 'top'
        })
      })
    },
    tornaguiasGet() {
      // this.$q.loading.show()
      this.loading = true
      this.$api.post('tornaguiaSearch', {
        fechaDesde: this.fecha1,
        fechaHasta: this.fecha2,
      }).then(response => {
        this.tornaguias = []
        response.data.forEach(d => {
          // d.fecha= date.formatDate(d.created_at, 'YYYY-MM-DD HH:mm:ss')
          this.tornaguias.push(d)
        })
        this.loading = false
        // this.$q.loading.hide()
      })
    },
    buscar() {
      this.tornaguiasGet()
    },
    tornaguiaDownload() {
      let data = [
        {
          columns: [
            // { label: "User", value: "user" }, // Top level data
            // { label: "Age", value: (row) => row.age + " years" }, // Custom format
            // { label: "Phone", value: (row) => (row.more ? row.more.phone || "" : "") }, // Run functions
            {label: "fecha", value: (row) => row.fecha + ' ' + (row.hora ? row.hora.substring(0, 5) : '')},
            {label: "numero", value: "numero"},
            {label: "yacimiento", value: "yacimiento"},
            {label: "tranca", value: "tranca"},
            {label: "cuadrilla", value: "cuadrilla"},
            {label: "tipoMaterial", value: "tipoMaterial"},
            {label: "minerales", value: "minerales"},
            {label: "peso", value: "peso"},
            {label: "sacos", value: "sacos"},
            {label: "transporte", value: (row) => row.transporte.placa},
            {label: "empresa", value: (row) => row.empresa.nombre},
            {label: "contratista", value: (row) => row.contratista.nombre},
            {label: "user", value: (row) => row.user.name},
            {label: "conductor", value: (row) => row.driver.name},
          ],
          content: this.tornaguias,
        },
      ]

      let settings = {
        fileName: "Tornaguia", // Name of the resulting spreadsheet
        // extraLength: 3, // A bigger number means that columns will be wider
        writeMode: 'writeFile', // The available parameters are 'WriteFile' and 'write'. This setting is optional. Useful in such cases https://docs.sheetjs.com/docs/solutions/output#example-remote-file
        writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
      }

      xlsx(data, settings) // Will download the excel file
    },
    tornaguiaClick() {
      this.tornaguia = {
        fecha: date.formatDate(new Date(), 'YYYY-MM-DD'),
        hora: date.formatDate(new Date(), 'HH:mm'),
        numero: '',
        departamento: 'Oruro',
        centroMinero: 'Poopo',
        yacimiento: '',
        trancaSalida: '',
        transporte_id: null,
        driver_id: null,
        empresa_id: null,
        contratista_id: null,
        nit: '',
        comprador: '',
        destino: '',
        tipoMaterial: 'Mineral',
        broza: '',
        sacos: '',
        mineralesSel: [],
        peso: '',
        cantidad: '',
        lote: '',
        user_id: this.store.user.id
      }
      this.showAddTornaguiaDialog = true
      this.tornaguiaCrear = true
    },
    tornaguiaCreate() {
      this.loading = true

      // Convertir array de minerales a string
      let materiales = ''
      this.tornaguia.mineralesSel.forEach((mineral) => {
        materiales += mineral + ','
      })
      this.tornaguia.minerales = materiales

      this.$api.post('tornaguia', this.tornaguia).then(response => {
        this.tornaguias.unshift(response.data)
        this.showAddTornaguiaDialog = false

        this.tornaguia = {
          fecha: date.formatDate(new Date(), 'YYYY-MM-DD'),
          mineralesSel: [],
        }

        this.loading = false
      }).catch(error => {
        this.loading = false
        this.$q.notify({
          color: 'negative',
          message: error.response?.data?.message || 'Error al guardar',
          icon: 'report_problem',
          position: 'top'
        })
      })
    },
    tornaguiaEdit(tornaguia) {
      this.tornaguia = tornaguia
      this.tornaguiaCrear = false
      this.showUpdateTornaguiaDialog = true
    },
    tornaguiaDelete(tornaguia) {
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar el usuario?',
        ok: 'Si',
        cancel: 'No'
      }).onOk(() => {
        // this.$q.loading.show()
        this.loading = true
        this.$api.delete('tornaguia/' + tornaguia.id).then(response => {
          this.tornaguiasGet()
        })
      })
    }
  },

}
</script>

<style scoped>
</style>
