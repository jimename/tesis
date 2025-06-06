<template>
  <q-page>
    <q-table :rows="drivers" :columns="driverColums" :filter="search" :rows-per-page-options="[0]" dense wrap-cells>
      <template v-slot:top-right>
        <q-toolbar>
          <q-btn v-if="store.permissions.includes('conductor create')" icon="add_circle_outline" @click="showAddDriverDialog = true;driverCrear=true"
            label="Agregar Conductor" color="green" class="q-mr-sm" dense no-caps />
          <q-input v-model="search"  outlined  dense placeholder="Buscar..." />
        </q-toolbar>
      </template>
      <template v-slot:body-cell-estado="props">
        <q-td :props="props" auto-width >
          <q-badge dense text-color="white" :color="props.row.estado=='Activo'?'green':'red'">{{props.row.estado}}</q-badge>
        </q-td>
      </template>
      <template v-slot:body-cell-option="props">
        <q-td :props="props" auto-width >
<!--          <q-btn-group flat>-->
<!--            <q-btn v-if="store.permissions.includes('conductor update')" flat dense icon="o_edit" @click="driverEdit(props.row)" size="10px" />-->
<!--            <q-btn v-if="store.permissions.includes('conductor delete')" flat dense icon="o_delete" @click="driverDelete(props.row)" size="10px" />-->
<!--          </q-btn-group>-->
          <q-btn-dropdown dense size="10px" label="Opciones" class="q-ml-xs" no-caps color="primary">
            <q-list>
              <q-item v-if="store.permissions.includes('conductor update')" clickable @click="driverEdit(props.row)" v-close-popup>
                <q-item-section avatar>
                  <q-icon name="edit" />
                </q-item-section>
                <q-item-section>Editar</q-item-section>
              </q-item>
              <q-item v-if="store.permissions.includes('conductor delete')" clickable @click="driverDelete(props.row)" v-close-popup>
                <q-item-section avatar>
                  <q-icon name="delete" />
                </q-item-section>
                <q-item-section>Eliminar</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>
    </q-table>
<!--    <pre>{{drivers}}</pre>-->
    <q-dialog v-model="showAddDriverDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Conductor</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="driverCreate">
            <q-input v-model="driver.name" hint="" required outlined label="Nombre" />
            <q-input v-model="driver.contacto" hint="" required outlined label="Contacto" />
<!--            <q-input v-model="driver.telefono" hint="" required outlined label="Telefono" />-->
            <q-input v-model="driver.celular" hint="" required outlined label="Celular" />
            <q-input v-model="driver.licencia" hint="" required outlined label="Licencia" />
            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="showUpdateDriverDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Modificar Conductor</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="driverUpdate">
            <q-input v-model="driver.name" hint="" required outlined label="Nombre" />
            <q-input v-model="driver.contacto" hint="" required outlined label="Contacto" />
<!--            <q-input v-model="driver.telefono" hint="" required outlined label="Telefono" />-->
            <q-input v-model="driver.celular" hint="" required outlined label="Celular" />
            <q-input v-model="driver.licencia" hint="" required outlined label="Licencia" />
<!--            estado Activo Inactivo-->
            <q-select v-model="driver.estado" :options="['Activo','Inactivo']" hint="" required outlined label="Estado" />
            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import {useCounterStore} from "stores/example-store";
export default {
  name: `Driver`,
  data () {
    return {
      store: useCounterStore(),
      roles: [
        'INSCRIPCION',
        'ACREDITACION',
        'REFRIGERIO',
        'ADMINISTRADOR',
      ],
      showAddDriverDialog: false,
      showUpdateDriverDialog: false,
      search: '',
      drivers: [],
      driver:{},
      loading: false,
      driverCrear: true,
      driverColums:[
        {name: 'option', field: 'option', label: 'Opciones', align: 'left', sortable: true},
        {name: 'estado', field: 'estado', label: 'Estado', align: 'left', sortable: true},
        {name: 'name', field: 'name', label: 'Nombre', align: 'left', sortable: true},
        {name: 'contacto', field: 'contacto', label: 'Contacto', align: 'left', sortable: true},
        // {name: 'telefono', field: 'telefono', label: 'Telefono', align: 'left', sortable: true},
        {name: 'celular', field: 'celular', label: 'Celular', align: 'left', sortable: true},
        {name: 'licencia', field: 'licencia', label: 'Licencia', align: 'left', sortable: true},
      ]
    }
  },
  methods:{
    driverUpdate(){
      this.loading = true
      this.$api.put(`driver/${this.driver.id}`,this.driver)
        .then(res=>{
          this.loading = false
          this.showUpdateDriverDialog = false
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'check_circle',
            message: 'Conductor actualizado'
          })
          this.driver = {}
          this.driversGet()
        })
        .catch(err=>{
          this.loading = false
          this.$q.notify({
            color: 'red-4',
            textColor: 'white',
            icon: 'error',
            message: 'Error al actualizar conductor'
          })
        })
    },
    driversGet(){
      this.$q.loading.show()
      this.$api.get('driver').then(response => {
        this.drivers = response.data
        this.$q.loading.hide()
      })
    },
    driverCreate(){
      if (this.driverCrear){
        this.loading = true
        this.$api.post('driver', this.driver).then(response => {
          this.driversGet()
          this.showAddDriverDialog = false
          this.driver = {}
          this.loading = false
        }).catch(error => {
          this.loading = false
          this.$q.notify({
            color: 'negative',
            message: error.response.data.message,
            icon: 'report_problem',
            position: 'top'
          })
        })
      }else{
        this.loading = true
        this.$api.put('driver/'+this.driver.id, this.driver).then(response => {
          this.driversGet()
          this.showAddDriverDialog = false
          this.driver = {}
          this.loading = false
        }).catch(error => {
          this.loading = false
          this.$q.notify({
            color: 'negative',
            message: error.response.data.message,
            icon: 'report_problem',
            position: 'top'
          })
        })
      }
    },
    driverEdit(driver){
      this.driver = {...driver}
      this.driverCrear = false
      this.showUpdateDriverDialog = true
    },
    updatePassword(driver){
      this.$q.dialog({
        title: 'Cambiar contraseña',
        message: 'Ingrese la nueva contraseña',
        prompt: {
          model: '',
          type: 'password'
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        this.$api.put(`updatePassword/${driver.id}`,{password:data})
          .then(res=>{
            this.$q.notify({
              color: 'green-4',
              textColor: 'white',
              icon: 'check_circle',
              position: 'top',
              message: 'Contraseña actualizada'
            })
          })
          .catch(err=>{
            this.$q.notify({
              color: 'red-4',
              textColor: 'white',
              icon: 'error',
              position: 'top',
              message: err.response.data.message
            })
          })
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismissed')
      })
    },
    driverDelete(driver){
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar el conductor?',
        ok: 'Si',
        cancel: 'No'
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.delete('driver/'+driver.id).then(response => {
          this.driversGet()
        })
      })
    }
  },
  created() {
    this.driversGet()
  }
}
</script>

<style scoped>
</style>
