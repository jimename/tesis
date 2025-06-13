<template>
  <q-page>
    <!-- Filtros superiores -->
    <q-card class="q-mb-md">
      <q-card-section>
        <div class="row q-col-gutter-sm">
          <div class="col-12 col-sm-3" v-if="store.permissions.includes('tornaguia read')">
            <q-input outlined dense type="date" v-model="fecha1" label="Fecha Desde" />
<!--            <pre>-->
<!--              {{$store.user.role}}-->
<!--                    roles: [-->
<!--        'Consejo vigilancia',-->
<!--        'Consejo administrativo',-->
<!--        'Secretariaria',-->
<!--        'Gerente',-->
<!--        'Administrador',-->
<!--      ],-->
<!--            </pre>-->
          </div>
          <div class="col-12 col-sm-3" v-if="store.permissions.includes('tornaguia read')">
            <q-input outlined dense type="date" v-model="fecha2" label="Fecha Hasta" />
          </div>
          <div class="col-12 col-sm-3">
            <q-select
              v-model="estadoAprobado"
              :options="['Aprobado', 'Pendiente']"
              label="Estado Aprobado"
              outlined
              dense
              clearable
            />
          </div>
          <div class="col-12 col-sm-3 flex flex-center" v-if="store.permissions.includes('tornaguia read')">
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
      <template v-slot:body-cell-aprobado="props">
        <q-td :props="props" auto-width>
          <q-badge dense text-color="white" :color="props.row.aprobado === 'Aprobado' ? 'green' : 'red'">
            {{ props.row.aprobado }}
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
          <q-space/>
          <q-btn icon="close" flat dense round @click="showAddTornaguiaDialog = false" v-close-popup/>
        </q-card-section>

        <q-card-section class="q-pa-sm">
          <q-form @submit.prevent="submitForm">
            <div class="row q-col-gutter-sm">
              <div class="col-12 col-md-2">
                <q-input v-model="tornaguia.fecha" type="date" label="Fecha" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 col-md-2">
                <q-input v-model="tornaguia.numero" label="Número" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 col-md-2">
                <q-input v-model="tornaguia.hora" type="time" label="Hora" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-select v-model="tornaguia.empresa_id" :options="empresas" option-label="label" option-value="value" map-options emit-value label="Empresa Destino" outlined dense :disable="role !== 'Secretariaria'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-select v-model="tornaguia.contratista_id" :options="contratistas" option-label="label" option-value="value" map-options emit-value label="Contratista" outlined dense :disable="role !== 'Consejo administrativo'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.yacimiento" label="Yacimiento" outlined dense :disable="role !== 'Secretariaria'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.trancaSalida" label="Tranca Salida" outlined dense :disable="role !== 'Consejo administrativo'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.cuadrilla" label="Cuadrilla" outlined dense/>
              </div>
              <div class="col-12 col-md-3">
                <q-select v-model="tornaguia.transporte_id" :options="transportes" option-label="label" option-value="value" map-options emit-value label="Transporte" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-select v-model="tornaguia.driver_id" :options="drivers" option-label="label" option-value="value" map-options emit-value label="Conductor" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
<!--              nit-->
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.nit" label="NIT" outlined dense :disable="role !== 'Consejo administrativo'"/>
              </div>
<!--              comprador-->
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.comprador" label="Comprador" outlined dense :disable="role !== 'Secretariaria'"/>
              </div>
<!--              departamento-->
              <div class="col-12 col-md-3">
                <q-select v-model="tornaguia.departamento" :options="['Oruro', 'Potosí', 'La Paz', 'Cochabamba', 'Chuquisaca', 'Tarija', 'Santa Cruz', 'Beni', 'Pando']" label="Departamento" outlined dense :disable="role !== 'Secretariaria'"/>
              </div>
<!--              centro minero-->
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.centroMinero" label="Centro Minero" outlined dense :disable="role !== 'Secretariaria'"/>
              </div>
              <!-- Tipo de Material -->
              <div class="col-12 row items-center q-gutter-sm">
                <q-radio v-model="tornaguia.tipoMaterial" val="Mineral" label="Mineral" :disable="role !== 'Consejo vigilancia'"/>
                <q-radio v-model="tornaguia.tipoMaterial" val="Embolsado" label="Embolsado" :disable="role !== 'Consejo vigilancia'"/>
              </div>

              <!-- Minerales -->
              <div class="col-12 row items-center q-gutter-sm">
                <q-checkbox v-for="mineral in ['Sn', 'Ag', 'Pb', 'Zn', 'Otros']" :key="mineral" v-model="tornaguia.mineralesSel" :val="mineral" :label="mineral" :disable="role !== 'Consejo vigilancia'"/>
              </div>

              <!-- Condicionales -->
              <div class="col-12 col-md-3" v-if="tornaguia.tipoMaterial === 'Mineral'">
                <q-input v-model="tornaguia.peso" type="number" label="Peso (Toneladas)" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 col-md-3" v-if="tornaguia.tipoMaterial === 'Embolsado'">
                <q-input v-model="tornaguia.sacos" type="number" label="Cantidad de Sacos" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.lote" label="N° de Lote" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="tornaguia.broza" label="Broza" outlined dense :disable="role !== 'Consejo vigilancia'"/>
              </div>
              <div class="col-12 row items-center q-gutter-sm">
                <q-radio v-model="tornaguia.tipo" val="Comercializacion" label="Comercializacion" :disable="role !== 'Consejo administrativo'"/>
                <q-radio v-model="tornaguia.tipo" val="Tratammiento" label="Tratammiento" :disable="role !== 'Consejo administrativo'"/>
              </div>
              <div class="col-12 col-md-4" v-if="tornaguia.tipo === 'Comercializacion'">
                <q-input v-model="tornaguia.observacionAdministrativo" label="Observación Administrativo" outlined dense :disable="role !== 'Consejo administrativo'"/>
              </div>
              <div class="col-12 col-md-4" v-if="tornaguia.tipo === 'Comercializacion'">
                <q-checkbox v-model="tornaguia.estadoAdministrativo" label="Estado Administrativo" false-value="Pendiente" true-value="Aprobado" :disable="role !== 'Consejo administrativo'"/>
              </div>
              <div class="col-12 col-md-4" v-if="tornaguia.tipo === 'Tratammiento'">
                <q-input v-model="tornaguia.observacionVigilancia" label="Observación Vigilancia" outlined dense :disable="role !== 'Consejo administrativo'"/>
              </div>
              <div class="col-12">
                <q-checkbox v-model="tornaguia.aprobado" label="Estado aprobado" false-value="Pendiente" true-value="Aprobado" :disable="role !== 'Gerente'"/>
              </div>
              <div class="col-12">
                <q-checkbox v-model="tornaguia.recibido" label="Estado recibido" false-value="Pendiente" true-value="Aprobado" :disable="role !== 'Secretariaria'"/>
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
      estadoAprobado: '',
      transportes: [],
      drivers: [],
      tornaguiaColums: [
        {name: 'option', field: 'option', label: 'Opciones', align: 'left', sortable: true},
        {name: 'numero', field: 'numero', label: 'No.', align: 'left', sortable: true},
        {name: 'aprobado', label: 'Estado Aprobado', field: 'aprobado', align: 'left', sortable: true},
        {
          name: 'fecha',
          field: row => row.fecha + ' ' + (row.hora ? row.hora.substring(0, 5) : ''),
          label: 'Fecha',
          align: 'left',
          sortable: true
        },
        // estado
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
  computed: {
    role() {
      return this.$store.user.role
    }
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
        aprobado: this.estadoAprobado
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
        centroMinero: 'San pablo',
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
        user_id: this.store.user.id,
        tipo: 'Comercializacion',
        estadoAdministrativo: 'Pendiente',
        observacionAdministrativo: '',
        observacionVigilancia: '',
        aprobado: 'Pendiente',
        recibido: 'Pendiente',
        minerales: '',
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
          {label: "transporte", value: row => row.transporte?.placa},
          {label: "empresa", value: row => row.empresa?.nombre},
          {label: "contratista", value: row => row.contratista?.nombre},
          {label: "user", value: row => row.user?.name},
          {label: "conductor", value: row => row.driver?.name},
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
      this.tornaguia = { ...tornaguia }

      this.tornaguia.transportes = tornaguia.transporte
        ? { label: tornaguia.transporte.placa, value: tornaguia.transporte.id }
        : null

      this.tornaguia.empresas = tornaguia.empresa
        ? { label: tornaguia.empresa.nombre, value: tornaguia.empresa.id }
        : null

      this.tornaguia.contratistas = tornaguia.contratista
        ? { label: tornaguia.contratista.nombre, value: tornaguia.contratista.id }
        : null

      this.tornaguia.drivers = tornaguia.driver
        ? { label: tornaguia.driver.name, value: tornaguia.driver.id }
        : null

      this.tornaguia.mineralesSel = tornaguia.minerales
        ? tornaguia.minerales.split(",")
        : []
    }
  },
}
</script>
