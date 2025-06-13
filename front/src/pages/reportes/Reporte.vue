<template>
  <q-page class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="text-h6">Reportes del Sistema</div>
        <div class="row q-col-gutter-md q-mt-sm">
          <div class="col-12 col-md-4">
            <q-select v-model="filtros.tipo" :options="tipos" label="Tipo de Reporte" outlined dense emit-value map-options />
          </div>
          <div class="col-12 col-md-2">
            <q-input v-model="filtros.fechaDesde" type="date" label="Fecha Desde" outlined dense />
          </div>
          <div class="col-12 col-md-2">
            <q-input v-model="filtros.fechaHasta" type="date" label="Fecha Hasta" outlined dense />
          </div>
          <div class="col-12 col-md-2">
            <q-select
              v-model="filtros.formato"
              :options="formatos"
              label="Formato"
              outlined
              dense
              emit-value
              map-options
            />
          </div>
          <div class="col-12 col-md-2 flex flex-center">
            <q-btn label="Buscar" color="primary" icon="search" @click="generarReporte" :loading="loading"/>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import moment from "moment";

export default {
  name: 'ReportePage',
  data() {
    return {
      filtros: {
        tipo: "minerales-asociado",
        fechaDesde: moment().subtract(1, 'month').format('YYYY-MM-DD'),
        fechaHasta: moment().format('YYYY-MM-DD'),
        formato: 'xlsx',
      },
      tipos: [
        { label: 'Minerales por Asociado', value: 'minerales-asociado' },
        { label: 'Tornaguías Emitidas', value: 'tornaguias-emitidas' },
        { label: 'Almacenamiento y Tratamiento', value: 'almacenamiento-tratamiento' },
        { label: 'Entregas a Comercializadoras', value: 'entregas-comercializadoras' },
        { label: 'Vehículos y Choferes', value: 'vehiculos-choferes' },
        { label: 'Producción Mensual', value: 'produccion-mensual' },
        { label: 'Control Documental', value: 'control-documental' },
        { label: 'Consolidado Dirección', value: 'consolidado-direccion' },
      ],
      formatos: [
        { label: 'Excel (.xlsx)', value: 'xlsx' },
        { label: 'PDF (.pdf)', value: 'pdf' },
      ],
      loading: false,
    };
  },
  methods: {
    generarReporte() {
      if (!this.filtros.tipo) {
        this.$q.notify({ type: 'warning', message: 'Seleccione el tipo de reporte' });
        return;
      }

      this.loading = true;
      const params = {
        fechaDesde: this.filtros.fechaDesde,
        fechaHasta: this.filtros.fechaHasta,
        format: this.filtros.formato
      };

      const url = process.env.API
      const endpoint = `${url}reportes/${this.filtros.tipo}`;
      const token = localStorage.getItem('token');
      this.$axios({
        url: endpoint,
        method: 'GET',
        params,
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        responseType: 'blob',
      }).then((res) => {
        const blob = new Blob([res.data]);
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${this.filtros.tipo}.${this.filtros.formato}`;
        link.click();
        URL.revokeObjectURL(link.href);
      }).catch(() => {
        this.$q.notify({ type: 'negative', message: 'Error al generar el reporte' });
      }).finally(() => {
        this.loading = false;
      });
    }
  }
}
</script>
