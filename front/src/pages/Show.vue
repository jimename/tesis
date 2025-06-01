<template>
  <q-layout view="lHh Lpr lFf" class="bg-grey-2">
    <q-header class="bg-grey-2">
    </q-header>
    <q-page-container>
      <q-page>
        <q-card>
          <q-card-section>
            <div class="row">
              <div class="col-12 col-sm-6">
                <q-input readonly  v-model="tornaguia.fecha" type="date" :label="`Fecha: ${tornaguia.fecha} `" outlined dense />
              </div>
              <div class="col-12 col-sm-6">
                <q-input readonly  v-model="tornaguia.numero" type="number" label="Número Asignado:" outlined dense />
              </div>
            </div>
          </q-card-section>
          <q-card-section class="bg-primary text-white q-pa-xs">
            <div class="text-subtitle2">PROPIETARIO DEL MINERAL</div>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col-12 col-sm-6">
                <q-select readonly  v-model="tornaguia.empresas" :rules="[ (val, rules) => !!val || 'Porfavor registrar dato' ]" required  label="Empresa Destiono:" outlined dense />
              </div>
              <div class="col-12 col-sm-6">
                <q-select readonly  v-model="tornaguia.contratistas"  :rules="[val=>!!val||'Porfavor registrar dato']" required  label="Contratista:" outlined dense />
              </div>
            </div>
          </q-card-section>
          <q-card-section class="bg-primary text-white q-pa-xs">
            <div class="text-subtitle2">PROCEDENCIA</div>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col-12 col-sm-4">
                <q-input readonly  v-model="tornaguia.yacimiento" label="Yacimiento:" outlined dense />
              </div>
              <div class="col-12 col-sm-4">
                <q-input readonly  v-model="tornaguia.tranca" label="Tranca:" outlined dense />
              </div>
              <div class="col-12 col-sm-4">
                <q-input readonly  v-model="tornaguia.cuadrilla" label="Cuadrilla:" outlined dense />
              </div>
            </div>
          </q-card-section>
          <q-card-section class="bg-primary text-white q-pa-xs">
            <div class="text-subtitle2">MEDIO DE TRANSPORTE</div>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col-12 col-sm-6">
                <q-select readonly  v-model="tornaguia.transportes" :rules="[val=>!!val||'Porfavor registrar dato']" required  label="Transporte:" outlined dense />
              </div>
              <div class="col-12 col-sm-6">
                <q-select readonly  v-model="tornaguia.drivers" :rules="[val=>!!val||'Porfavor registrar dato']"  required  label="Conductor:" outlined dense />
              </div>
            </div>
          </q-card-section>
          <q-card-section class="bg-primary text-white q-pa-xs">
            <div class="text-subtitle2">TIPO DE MATERIAL</div>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="text-bold">Tipo de Material:</div>
                <q-radio disable  v-model="tornaguia.tipoMaterial" val="Mineral" label="Mineral" />
                <q-radio disable  v-model="tornaguia.tipoMaterial" val="Embolsado" label="Embolsado" />
              </div>
              <div class="col-12 col-sm-6">
                <div class="text-bold">Minerales:</div>
                <q-checkbox disable  v-model="tornaguia.mineralesSel" val="Sn" label="Sn" />
                <q-checkbox disable  v-model="tornaguia.mineralesSel" val="Ag" label="Ag" />
                <q-checkbox disable  v-model="tornaguia.mineralesSel" val="Pb" label="Pb" />
                <q-checkbox disable  v-model="tornaguia.mineralesSel" val="Zn" label="Zn" />
                <q-checkbox disable  v-model="tornaguia.mineralesSel" val="Otros" label="Otros" />
                <!--                    <q-input readonly  v-model="tornaguia.minerales" disable label="Minerales:" outlined dense />-->
              </div>
              <div class="col-12 col-sm-6">
                <q-input readonly  v-model="tornaguia.peso" type="number" label="Peso en Tn.:" outlined dense />
              </div>
              <div class="col-12 col-sm-6">
                <q-input readonly  v-model="tornaguia.sacos" type="number" label="Cantidad de Sacos:" outlined dense />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>

  </q-layout>
</template>

<script>
export default {
  name: `Show`,
  data() {
    return {
      id: this.$route.params.id,
      tornaguia: {}
    };
  },
  created(){
    this.$q.loading.show()
    this.$api.get(`tornaguia/${this.id}`).then((response) => {
      console.log(response.data);
      this.$q.loading.hide()
      let tornaguia = response.data
      this.tornaguia = response.data;
      this.tornaguia.transportes={label:tornaguia.transporte.placa, value:tornaguia.transporte.id}
      this.tornaguia.empresas={label:tornaguia.empresa.nombre, value:tornaguia.empresa.id}
      this.tornaguia.contratistas={label:tornaguia.contratista.nombre, value:tornaguia.contratista.id}
      this.tornaguia.drivers={label:tornaguia.driver.name, value:tornaguia.driver.id}
      this.tornaguia.mineralesSel=tornaguia.minerales.split(",")
    });

  },
}
</script>

<style scoped>

</style>
