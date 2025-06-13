<template>
  <q-page>
    <!-- Filtros superiores -->
    <q-card class="q-mb-md">
      <q-card-section>
        <div class="row q-col-gutter-sm">
          <div class="col-12 col-sm-4" v-if="store.permissions.includes('tornaguia read')">
            <q-input outlined dense type="date" v-model="fecha1" label="Fecha Desde" />
          </div>
          <div class="col-12 col-sm-4" v-if="store.permissions.includes('tornaguia read')">
            <q-input outlined dense type="date" v-model="fecha2" label="Fecha Hasta" />
          </div>
          <div class="col-12 col-sm-4 flex flex-center" v-if="store.permissions.includes('tornaguia read')">
            <q-btn icon="search" color="primary" label="Buscar" @click="buscar" :loading="loading" />
          </div>
        </div>
      </q-card-section>
    </q-card>

    <!-- Tabla -->
    <q-table :rows="tornaguias" :columns="tornaguiaColums" :filter="search" :rows-per-page-options="[0]" dense>
      <template v-slot:top-right>
        <q-toolbar class="q-gutter-sm">
          <q-btn v-if="store.permissions.includes('tornaguia read')" color="green" :label="$q.screen.lt.md ? '' : 'Excel'" icon="download" @click="tornaguiaDownload" :loading="loading" no-caps />
          <q-btn v-if="store.permissions.includes('tornaguia create')" color="primary" :label="$q.screen.lt.md ? '' : 'Tornaguia'" icon="add_circle_outline" @click="tornaguiaClick" :loading="loading" no-caps />
          <q-input v-model="search" outlined dense placeholder="Buscar..." />
        </q-toolbar>
      </template>

      <template v-slot:body-cell-tipoMaterial="props">
        <q-td :props="props" auto-width>
          <q-badge dense text-color="white" :color="props.row.tipoMaterial === 'Embolsado' ? 'green' : 'red'">
            {{ props.row.tipoMaterial }}
          </q-badge>
        </q-td>
      </template>

      <template v-slot:body-cell-option="props">
        <q-td :props="props" auto-width>
          <q-btn-dropdown color="primary" label="Opciones" no-caps dense size="10px">
            <q-list>
              <q-item v-if="store.permissions.includes('tornaguia update')" clickable v-close-popup @click="ver(props.row)">
                <q-item-section avatar><q-icon name="o_edit"/></q-item-section>
                <q-item-section>Modificar</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('tornaguia read')" clickable v-close-popup @click="abrirVista(props.row)">
                <q-item-section avatar><q-icon name="o_visibility"/></q-item-section>
                <q-item-section>Ver</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('tornaguia read')" clickable v-close-popup @click="abrirImpresion(props.row)">
                <q-item-section avatar><q-icon name="o_print"/></q-item-section>
                <q-item-section>Imprimir hoja</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>
    </q-table>

    <!-- Diálogo: Crear o Editar Tornaguia -->
    <q-dialog v-model="showAddTornaguiaDialog" full-width>
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">{{ tornaguiaCrear ? 'Generación de' : 'Modificar' }} Tornaguia o Cartaporte</div>
        </q-card-section>

        <q-card-section class="q-pa-xs">
          <q-form @submit.prevent="submitForm">
            <div class="row q-col-gutter-sm">
              <div class="col-6 col-md-2" v-for="(field, index) in formFields" :key="index">
                <component :is="field.type" v-model="tornaguia[field.model]" v-bind="field.props" />
              </div>

              <!-- Tipo de Material -->
              <div class="col-12 col-md-3">
                <q-radio v-model="tornaguia.tipoMaterial" val="Mineral" label="Mineral"/>
                <q-radio v-model="tornaguia.tipoMaterial" val="Embolsado" label="Embolsado"/>
              </div>

              <!-- Minerales -->
              <div class="col-12 col-md-3">
                <q-checkbox v-for="mineral in ['Sn', 'Ag', 'Pb', 'Zn', 'Otros']" :key="mineral"
                            v-model="tornaguia.mineralesSel" :val="mineral" :label="mineral"/>
              </div>

              <!-- Condicionales -->
              <div class="col-12 col-md-3" v-if="tornaguia.tipoMaterial === 'Mineral'">
                <q-input v-model="tornaguia.peso" type="number" label="Peso (Toneladas)" outlined dense/>
              </div>

              <div class="col-12 col-md-3" v-if="tornaguia.tipoMaterial === 'Embolsado'">
                <q-input v-model="tornaguia.sacos" type="number" label="Cantidad de Sacos" outlined dense/>
              </div>
            </div>

            <q-card-actions align="right">
              <q-btn label="Cancelar" flat v-close-popup/>
              <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" :label="tornaguiaCrear ? 'Guardar' : 'Modificar'" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <!-- Contenedor para impresión -->
    <div id="myelement" class="hidden"></div>
  </q-page>
</template>
<script>
import {date} from "quasar";
import {useCounterStore} from "stores/example-store";
import xlsx from "json-as-xlsx"
import moment from "moment";

export default {
  name: `Tornaguia`,
  data() {
    return {
      store: useCounterStore(),
      showAddTornaguiaDialog: false,
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
        {name: 'contratista', label: 'Contratista', field: row => row.contratista?.nombre, align: 'left', sortable: true},
        {name: 'minerales', label: 'Minerales', field: 'minerales', align: 'left', sortable: true},
        {name: 'tipoMaterial', label: 'Tipo Material', field: 'tipoMaterial', align: 'left', sortable: true},
        {name: 'transporte', label: 'Transporte', field: row => row.transporte?.placa, align: 'left', sortable: true},
        {name: 'conductor', label: 'Conductor', field: row => row.driver?.name, align: 'left', sortable: true},
        {name: 'user', label: 'Usuario', field: row => row.user?.name, align: 'left', sortable: true},
      ]
    }
  },
  mounted() {
    this.tornaguiasGet()
    this.$api.get('datos').then((response) => {
      this.empresas = response.data.empresas.map(e => ({ label: e.nombre, value: e.id }))
      this.contratistas = response.data.contratistas.map(c => ({ label: c.nombre, value: c.id }))
      this.transportes = response.data.transportes.map(t => ({ label: t.placa, value: t.id }))
      this.drivers = response.data.drivers.map(d => ({ label: d.name, value: d.id }))
    });
  },
  methods: {
    submitForm() {
      if (this.tornaguiaCrear) this.tornaguiaCreate()
      else this.tornaguiaUpdate()
    },
    tornaguiasGet() {
      this.loading = true
      this.$api.post('tornaguiaSearch', {
        fechaDesde: this.fecha1,
        fechaHasta: this.fecha2,
      }).then(response => {
        this.tornaguias = response.data
        this.loading = false
      })
    },
    buscar() {
      this.tornaguiasGet()
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
      this.tornaguia.minerales = this.tornaguia.mineralesSel.join(',')
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
    tornaguiaUpdate() {
      this.loading = true
      this.tornaguia.minerales = this.tornaguia.mineralesSel.join(',')
      this.$api.put('tornaguia/' + this.tornaguia.id, this.tornaguia).then(() => {
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
    tornaguiaDownload() {
      let data = [{
        columns: [
          {label: "fecha", value: row => row.fecha + ' ' + (row.hora ? row.hora.substring(0, 5) : '')},
          {label: "numero", value: "numero"},
          {label: "yacimiento", value: "yacimiento"},
          {label: "tranca", value: "tranca"},
          {label: "cuadrilla", value: "cuadrilla"},
          {label: "tipoMaterial", value: "tipoMaterial"},
          {label: "minerales", value: "minerales"},
          {label: "peso", value: "peso"},
          {label: "sacos", value: "sacos"},
          {label: "transporte", value: row => row.transporte.placa},
          {label: "empresa", value: row => row.empresa.nombre},
          {label: "contratista", value: row => row.contratista.nombre},
          {label: "user", value: row => row.user.name},
          {label: "conductor", value: row => row.driver.name},
        ],
        content: this.tornaguias,
      }]

      let settings = {
        fileName: "Tornaguia",
        writeMode: 'writeFile',
        writeOptions: {},
      }
      xlsx(data, settings)
    },
    abrirVista(item) {
      window.open(`${process.env.API}tornaguia/vista/${item.id}`, '_blank')
    },
    abrirImpresion(item) {
      window.open(`${process.env.API}tornaguia/print/${item.id}`, '_blank')
    },
    ver(tornaguia) {
      this.showAddTornaguiaDialog = true
      this.tornaguiaCrear = false
      this.tornaguia = tornaguia
      this.tornaguia.transportes = {label: tornaguia.transporte.placa, value: tornaguia.transporte.id}
      this.tornaguia.empresas = {label: tornaguia.empresa.nombre, value: tornaguia.empresa.id}
      this.tornaguia.contratistas = {label: tornaguia.contratista.nombre, value: tornaguia.contratista.id}
      this.tornaguia.drivers = {label: tornaguia.driver.name, value: tornaguia.driver.id}
      this.tornaguia.mineralesSel = tornaguia.minerales ? tornaguia.minerales.split(",") : []
    },
  },
}
</script>
