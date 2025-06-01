<template>
  <q-page>
    <q-table :rows="empresas" :columns="empresaColums" :filter="search" :rows-per-page-options="[0]" dense wrap-cells>
      <template v-slot:top-right>
        <q-toolbar>
          <q-btn v-if="store.permissions.includes('empresa create')" flat icon="add_circle_outline" @click="showAddUserDialog = true;empresaCrear=true" />
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
            <q-btn v-if="store.permissions.includes('empresa update')" flat dense icon="o_edit" @click="empresaEdit(props.row)" size="10px" />
            <q-btn v-if="store.permissions.includes('empresa delete')" flat dense icon="o_delete" @click="empresaDelete(props.row)" size="10px" />
          </q-btn-group>
        </q-td>
      </template>
    </q-table>
    <q-dialog v-model="showAddUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Empresa</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="empresaCreate">
            <q-input v-model="empresa.nombre" hint="" required outlined label="Nombre" />
            <q-select v-model="empresa.ciudad" hint="" required outlined label="Ciudad" :options="['ORURO','LA PAZ','COCHABAMBA','SANTA CRUZ','POTOSI','TARIJA','CHUQUISACA','BENI','PANDO']" />
            <q-input v-model="empresa.telefono" hint="" required outlined label="Celular" />
            <q-input v-model="empresa.nit" hint="" required outlined label="NIT" />
            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="showUpdateUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Empresa</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="empresaUpdate">
            <q-input v-model="empresa.nombre" hint="" required outlined label="Nombre" />
            <q-select v-model="empresa.ciudad" hint="" required outlined label="Ciudad" :options="['ORURO','LA PAZ','COCHABAMBA','SANTA CRUZ','POTOSI','TARIJA','CHUQUISACA','BENI','PANDO']" />
            <q-input v-model="empresa.telefono" hint="" required outlined label="Celular" />
            <q-input v-model="empresa.nit" hint="" required outlined label="NIT" />
            <q-select v-model="empresa.estado" :options="['Activo','Inactivo']" hint="" required outlined label="Estado" />
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
  name: `User`,
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
      empresas: [],
      empresa:{},
      loading: false,
      empresaCrear: true,
      empresaColums:[
        {name: 'option', field: 'option', label: 'Opciones', align: 'left', sortable: true},
        {name: 'estado', field: 'estado', label: 'Estado', align: 'left', sortable: true},
        {name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true},
        {name: 'nombre', label: 'Nombre', field: 'nombre', align: 'left', sortable: true},
        {name: 'ciudad', label: 'Ciudad', field: 'ciudad', align: 'left', sortable: true},
      ]
    }
  },
  methods:{
    empresaUpdate(){
      this.loading = true
      this.$api.put(`empresa/${this.empresa.id}`,this.empresa)
        .then(res=>{
          this.loading = false
          this.showUpdateUserDialog = false
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'check_circle',
            message: 'Empresa actualizado'
          })
          this.empresa = {}
          this.empresasGet()
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
    empresasGet(){
      this.$q.loading.show()
      this.$api.get('empresa').then(response => {
        this.empresas = response.data
        this.$q.loading.hide()
      })
    },
    empresaCreate(){
      if (this.empresaCrear){
        this.loading = true
        this.$api.post('empresa', this.empresa).then(response => {
          this.empresasGet()
          this.showAddUserDialog = false
          this.empresa = {}
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
        this.$api.put('empresa/'+this.empresa.id, this.empresa).then(response => {
          this.empresasGet()
          this.showAddUserDialog = false
          this.empresa = {}
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
    empresaEdit(empresa){
      this.empresa = empresa
      this.empresaCrear = false
      this.showUpdateUserDialog = true
    },
    updatePassword(empresa){
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
        this.$api.put(`updatePassword/${empresa.id}`,{password:data})
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
    empresaDelete(empresa){
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar el usuario?',
        ok: 'Si',
        cancel: 'No'
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.delete('empresa/'+empresa.id).then(response => {
          this.empresasGet()
        })
      })
    }
  },
  created() {
    this.empresasGet()
  }
}
</script>

<style scoped>
</style>
