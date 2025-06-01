<template>
  <q-page>
    <div class="row q-pa-xs">
      <div class="col-12 col-sm-3" v-for="d in datos" :key="d.id">
        <q-card class="q-ma-xs">
          <q-card-section class="q-pa-none">
            <div class="row">
              <div :class="`col-5 q-pr-lg bg-${d.color} q-pa-lg flex flex-center`">
                <q-icon :name="`${d.icon}`" size="80px" color="white"  />
              </div>
              <div class="col-7 q-pa-md">
                <div class="text-h6">{{d.nombre}}</div>
                <div class="text-subtitle2">Cantidad ( {{d.cantidad}} )</div>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12">
        <q-card>
          <q-card-section class="q-pa-xs">
            <div class="row">
              <div class="col-12 col-sm-4">
                <q-input v-model="fechaIni" label="Fecha Inicial" type="date" dense outlined />
              </div>
              <div class="col-12 col-sm-4">
                <q-input v-model="fechaFin" label="Fecha Final" type="date" dense outlined/>
              </div>
              <div class="col-12 col-sm-4 text-right">
                <q-btn label="Buscar" color="primary" @click="tornaguiasGet" no-caps icon="search" :loading="loading" />
              </div>
              <div class="col-12">
                <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import moment from "moment";

export default {
  name: `IndexPage`,
  data () {
    return {
      series: [{
        name: 'Cash Flow',
        data: [
          //   1.45, 5.42, 5.9, -0.42, -12.6, -18.1, -18.2, -14.16, -11.1, -6.09, 0.34, 3.88, 13.07,
          // 5.8, 2, 7.37, 8.1, 13.57, 15.75, 17.1, 19.8, -27.03, -54.4, -47.2, -43.3, -18.6, -
          //     48.6, -41.1, -39.6, -37.6, -29.4, -21.4, -2.4
        ]
      }],
      chartOptions: {
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            colors: {
              ranges: [{
                from: -100,
                to: -46,
                color: '#F15B46'
              }, {
                from: -45,
                to: 0,
                color: '#FEB019'
              }]
            },
            columnWidth: '80%',
          }
        },
        dataLabels: {
          enabled: false,
        },
        yaxis: {
          title: {
            text: 'Growth',
          },
          labels: {
            formatter: function (y) {
              return y.toFixed(0) + "";
            }
          }
        },
        xaxis: {
          type: 'datetime',
          categories: [
            // '2011-01-01', '2011-02-01', '2011-03-01', '2011-04-01', '2011-05-01', '2011-06-01',
            // '2011-07-01', '2011-08-01', '2011-09-01', '2011-10-01', '2011-11-01', '2011-12-01',
            // '2012-01-01', '2012-02-01', '2012-03-01', '2012-04-01', '2012-05-01', '2012-06-01',
            // '2012-07-01', '2012-08-01', '2012-09-01', '2012-10-01', '2012-11-01', '2012-12-01',
            // '2013-01-01', '2013-02-01', '2013-03-01', '2013-04-01', '2013-05-01', '2013-06-01',
            // '2013-07-01', '2013-08-01', '2013-09-01'
          ],
          labels: {
            rotate: -90
          }
        }
      },
      // el incio de un mes pasado
      fechaIni: moment().startOf('month').subtract(1, 'month').format('YYYY-MM-DD'),
      fechaFin: moment().endOf('month').subtract(1, 'month').format('YYYY-MM-DD'),
      loading: false,
      tornaguias: [],
      datos: [
        {
          id: 1,
          nombre: 'Tornaguias',
          cantidad: 0,
          color: 'info',
          icon: 'o_description'
        },
        {
          id: 2,
          nombre: 'Trasportes',
          cantidad: 0,
          color: 'warning',
          icon: 'o_directions_bus_filled'
        },
        {
          id: 3,
          nombre: 'Contratistas',
          cantidad: 0,
          color: 'positive',
          icon: 'work_outline'
        },
        {
          id: 4,
          nombre: 'Empresas',
          cantidad: 0,
          color: 'negative',
          icon: 'o_business'
        },
      ]
    }
  },
  created() {
    // this.$q.loading.show()
    this.loading = true
    this.$api.get('totales').then(res => {
      this.datos[0].cantidad = res.data.totalTornaguias
      this.datos[1].cantidad = res.data.totalTrasportes
      this.datos[2].cantidad = res.data.totalContratistas
      this.datos[3].cantidad = res.data.totalEmpresas
    }).finally(() => {
      // this.$q.loading.hide()
      this.loading = false
    })
    // this.$api.get('tornaguia').then(res => {
    //   this.datos[0].cantidad = res.data.length
    // })
    // this.$api.get('transporte').then(res => {
    //   this.datos[1].cantidad = res.data.length
    // })
    // this.$api.get('contratista').then(res => {
    //   this.datos[2].cantidad = res.data.length
    // })
    // this.$api.get('empresa').then(res => {
    //   this.datos[3].cantidad = res.data.length
    // })
    this.tornaguiasGet()
  },
  methods: {
    tornaguiasGet() {
      this.loading = true
      this.$api.get('tornaguiasGet', {
        params: {
          fechaIni: this.fechaIni,
          fechaFin: this.fechaFin
        }
      }).then(res => {
        console.log(res.data)
        const newData = [];
        const newCategories = [];

        // Llenar los nuevos arrays con los datos de la respuesta
        res.data.forEach((item) => {
          newData.push(item.total);
          newCategories.push(item.fecha);
        });

        // Asignar los nuevos arrays para que Vue detecte el cambio
        this.series[0].data = newData;
        this.chartOptions = {
          ...this.chartOptions,
          xaxis: {
            ...this.chartOptions.xaxis,
            categories: newCategories
          }
        };
      }).finally(() => {
        this.loading = false
      })
    }
  }
}
</script>
