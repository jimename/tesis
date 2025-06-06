<template>
  <q-page>
    <q-table :rows="contratistas" :columns="contratistaColums" :filter="search" :rows-per-page-options="[0]" dense wrap-cells>
      <template v-slot:top-right>
        <q-toolbar>
          <q-btn v-if="store.permissions.includes('contratista create')" icon="add_circle_outline" @click="showAddUserDialog = true;contratistaCrear=true"
            label="Agregar Contratista" color="green" class="q-mr-sm" dense no-caps/>
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
<!--            <q-btn v-if="store.permissions.includes('contratista update')" flat dense icon="o_edit" @click="contratistaEdit(props.row)" size="10px"/>-->
<!--            <q-btn v-if="store.permissions.includes('contratista delete')" flat dense icon="o_delete" @click="contratistaDelete(props.row)" size="10px"/>-->
            <q-btn-dropdown label="Opciones" color="primary" text-color="white" no-caps size="xs" dense>
              <q-list>
                <q-item v-if="store.permissions.includes('contratista update')" clickable @click="contratistaEdit(props.row)" v-close-popup>
                  <q-item-section avatar>
                    <q-icon name="edit" />
                  </q-item-section>
                  <q-item-section>Editar</q-item-section>
                </q-item>
                <q-item v-if="store.permissions.includes('contratista delete')" clickable @click="contratistaDelete(props.row)" v-close-popup>
                  <q-item-section avatar>
                    <q-icon name="delete" />
                  </q-item-section>
                  <q-item-section>Eliminar</q-item-section>
                </q-item>
              </q-list>

            </q-btn-dropdown>
          </q-btn-group>
        </q-td>
      </template>
    </q-table>
    <q-dialog v-model="showAddUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Contratista</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="contratistaCreate">
            <q-input v-model="contratista.nombre" hint="" required outlined label="Nombre" />
            <q-input v-model="contratista.cuadrilla" hint="" required outlined label="Cuadrilla" />
            <q-input v-model="contratista.celular" hint="" required outlined label="Celular" />
            <q-input v-model="contratista.direccion" hint="" required outlined label="Direccion" />
            <q-input v-model="contratista.ci" hint="" required outlined label="CI" />
            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="showUpdateUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Contratista</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="contratistaUpdate">
            <q-input v-model="contratista.nombre" hint="" required outlined label="Nombre" />
            <q-input v-model="contratista.cuadrilla" hint="" required outlined label="Cuadrilla" />
            <q-input v-model="contratista.celular" hint="" required outlined label="Celular" />
            <q-input v-model="contratista.direccion" hint="" required outlined label="Direccion" />
            <q-input v-model="contratista.ci" hint="" required outlined label="CI" />
<!--            estadp Activo Inactivo-->
            <q-select v-model="contratista.estado" :options="['Activo','Inactivo']" hint="" required outlined label="Estado" />
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
      contratistas: [],
      contratista:{},
      loading: false,
      contratistaCrear: true,
      contratistaColums:[
        {name: 'option', field: 'option', label: 'Opciones', align: 'left', sortable: true},
        {name: 'estado', field: 'estado', label: 'Estado', align: 'left', sortable: true},
        {name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true},
        {name: 'nombre', label: 'Nombre', field: 'nombre', align: 'left', sortable: true},
        {name: 'cuadrilla', label: 'Cuadrilla', field: 'cuadrilla', align: 'left', sortable: true},
        {name: 'celular', label: 'Celular', field: 'celular', align: 'left', sortable: true},
        {name: 'direccion', label: 'Direccion', field: 'direccion', align: 'left', sortable: true},
        {name: 'ci', label: 'CI', field: 'ci', align: 'left', sortable: true},
      ]
    }
  },
  methods:{
    contratistaUpdate(){
      this.loading = true
      // console.log(this.contratista)
      this.$api.put(`contratista/${this.contratista.id}`,this.contratista)
        .then(res=>{
          console.log(res.data)
          this.loading = false
          this.showUpdateUserDialog = false
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'check_circle',
            message: 'Contratista actualizado'
          })
          this.contratista = {}
          this.contratistasGet()
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
    contratistasGet(){
      this.$q.loading.show()
      this.$api.get('contratista').then(response => {
        this.contratistas = response.data
        this.$q.loading.hide()
      })
    },
    contratistaCreate(){
      if (this.contratistaCrear){
        this.loading = true
        this.$api.post('contratista', this.contratista).then(response => {
          this.contratistasGet()
          this.showAddUserDialog = false
          this.contratista = {}
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
        this.$api.put('contratista/'+this.contratista.id, this.contratista).then(response => {
          this.contratistasGet()
          this.showAddUserDialog = false
          this.contratista = {}
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
    contratistaEdit(contratista){
      this.contratista = {...contratista}
      this.contratistaCrear = false
      this.showUpdateUserDialog = true
    },
    updatePassword(contratista){
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
        this.$api.put(`updatePassword/${contratista.id}`,{password:data})
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
    contratistaDelete(contratista){
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar el usuario?',
        ok: 'Si',
        cancel: 'No'
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.delete('contratista/'+contratista.id).then(response => {
          this.contratistasGet()
        })
      })
    }
  },
  created() {
    this.contratistasGet()
  }
}
</script>

<style scoped>
</style>
