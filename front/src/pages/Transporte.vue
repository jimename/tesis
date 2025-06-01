<template>
  <q-page>
    <q-table :rows="transportes" :columns="transporteColums" :filter="search" :rows-per-page-options="[0]" dense wrap-cells>
      <template v-slot:top-right>
        <q-toolbar>
          <q-btn v-if="store.permissions.includes('transporte create')" flat icon="add_circle_outline" @click="showAddUserDialog = true;transporteCrear=true" />
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
          <q-btn-group flat>
            <q-btn v-if="store.permissions.includes('transporte update')" flat dense icon="o_edit" @click="transporteEdit(props.row)" size="10px" />
            <q-btn v-if="store.permissions.includes('transporte delete')" flat dense icon="o_delete" @click="transporteDelete(props.row)" size="10px" />
          </q-btn-group>
        </q-td>
      </template>
    </q-table>
    <q-dialog v-model="showAddUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Transporte</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="transporteCreate">
            <q-input v-model="transporte.tipo" hint="" required outlined label="Tipo" />
            <q-input v-model="transporte.marca" hint="" required outlined label="Marca" />
            <q-input v-model="transporte.placa" hint="" required outlined label="Placa" />
            <q-input v-model="transporte.color" hint="" required outlined label="Color" />
            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="showUpdateUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Modificar Transporte</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="transporteUpdate">
            <q-input v-model="transporte.tipo" hint="" required outlined label="Tipo" />
            <q-input v-model="transporte.marca" hint="" required outlined label="Marca" />
            <q-input v-model="transporte.placa" hint="" required outlined label="Placa" />
            <q-input v-model="transporte.color" hint="" required outlined label="Color" />
<!--            estado Activo Inactivo-->
            <q-select v-model="transporte.estado" :options="['Activo','Inactivo']" hint="" required outlined label="Estado" />
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
  name: `Transporte`,
  data () {
    return {
      store: useCounterStore(),
      roles: [
        'INSCRIPCION',
        'ACREDITACION',
        'REFRIGERIO',
        'ADMINISTRADOR',
      ],
      showAddUserDialog: false,
      showUpdateUserDialog: false,
      search: '',
      transportes: [],
      transporte:{},
      loading: false,
      transporteCrear: true,
      transporteColums:[
        {name: 'option', field: 'option', label: 'Opciones', align: 'left', sortable: true},
        {name: 'estado', field: 'estado', label: 'Estado', align: 'left', sortable: true},
        {name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true},
        {name: 'tipo', label: 'Tipo', field: 'tipo', align: 'left', sortable: true},
        {name: 'marca', label: 'Marca', field: 'marca', align: 'left', sortable: true},
        {name: 'placa', label: 'Placa', field: 'placa', align: 'left', sortable: true},
        {name: 'color', label: 'Color', field: 'color', align: 'left', sortable: true},
      ]
    }
  },
  methods:{
    transporteUpdate(){
      this.loading = true
      this.$api.put(`transporte/${this.transporte.id}`,this.transporte)
        .then(res=>{
          this.loading = false
          this.showUpdateUserDialog = false
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'check_circle',
            message: 'Transporte actualizado'
          })
          this.transporte = {}
          this.transportesGet()
        })
        .catch(err=>{
          this.loading = false
          this.$q.notify({
            color: 'red-4',
            textColor: 'white',
            icon: 'error',
            message: 'Error al actualizar usuario'
          })
        })
    },
    transportesGet(){
      this.$q.loading.show()
      this.$api.get('transporte').then(response => {
        this.transportes = response.data
        this.$q.loading.hide()
      })
    },
    transporteCreate(){
      if (this.transporteCrear){
        this.loading = true
        this.$api.post('transporte', this.transporte).then(response => {
          this.transportesGet()
          this.showAddUserDialog = false
          this.transporte = {}
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
        this.$api.put('transporte/'+this.transporte.id, this.transporte).then(response => {
          this.transportesGet()
          this.showAddUserDialog = false
          this.transporte = {}
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
    transporteEdit(transporte){
      this.transporte = transporte
      this.transporteCrear = false
      this.showUpdateUserDialog = true
    },
    updatePassword(transporte){
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
        this.$api.put(`updatePassword/${transporte.id}`,{password:data})
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
    transporteDelete(transporte){
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar el usuario?',
        ok: 'Si',
        cancel: 'No'
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.delete('transporte/'+transporte.id).then(response => {
          this.transportesGet()
        })
      })
    }
  },
  created() {
    this.transportesGet()
  }
}
</script>

<style scoped>
</style>
