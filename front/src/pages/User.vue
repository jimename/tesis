<template>
  <q-page>
    <q-table :rows="users" :columns="userColums" :filter="search" :rows-per-page-options="[0]" dense wrap-cells>
      <template v-slot:top-right>
        <q-toolbar>
          <q-btn v-if="store.permissions.includes('user create')" icon="add_circle_outline" @click="showAddUserDialog = true;userCrear=true"
                 dense label="Agregar Usuario" class="q-mr-sm" color="green" no-caps />
          <q-input v-model="search"  outlined  dense placeholder="Buscar..." />
        </q-toolbar>
      </template>
      <template v-slot:body-cell-permission="props">
        <q-td :props="props" >
          <ul style="list-style: none;">
            <li class="q-pa-none q-ma-none" v-for="p in props.row.permissions" :key="p.id">{{p.name}}</li>
          </ul>
        </q-td>
      </template>
      <template v-slot:body-cell-option="props">
        <q-td :props="props" >
<!--          <q-btn v-if="store.permissions.includes('user update')" flat dense icon="o_edit" @click="userEdit(props.row)" />-->
<!--          <q-btn v-if="store.permissions.includes('user delete')" flat dense icon="o_delete" @click="userDelete(props.row)" />-->
<!--          <q-btn v-if="store.permissions.includes('user update')" flat dense icon="o_key" @click="updatePassword(props.row)" />-->
<!--          <q-btn flat dense v-if="store.permissions.includes('user update')" icon="o_lock" @click="updatePermission(props.row)" />-->
          <q-btn-dropdown v-if="store.permissions.includes('user update')" dense icon="more_vert" color="primary" no-caps
            label="Opciones" class="q-ml-sm">
          >
            <q-list>
              <q-item clickable v-ripple @click="userEdit(props.row)" v-close-popup>
                <q-item-section avatar>
                  <q-icon name="edit" />
                </q-item-section>
                <q-item-section>Editar</q-item-section>
              </q-item>
              <q-item clickable v-ripple @click="userDelete(props.row)" v-close-popup>
                <q-item-section avatar>
                  <q-icon name="delete" />
                </q-item-section>
                <q-item-section>Eliminar</q-item-section>
              </q-item>
              <q-item clickable v-ripple @click="updatePassword(props.row)" v-close-popup>
                <q-item-section avatar>
                  <q-icon name="key" />
                </q-item-section>
                <q-item-section>Cambiar contraseña</q-item-section>
              </q-item>
              <q-item clickable v-ripple @click="updatePermission(props.row)" v-close-popup>
                <q-item-section avatar>
                  <q-icon name="lock" />
                </q-item-section>
                <q-item-section>Modificar permisos</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>
    </q-table>
    <q-dialog v-model="showAddUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Usuario</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="userCreate">
            <q-input v-model="user.name" hint="" required outlined label="Nombre" />
            <q-input v-model="user.email" hint="" required outlined label="Email" />
            <q-input v-model="user.username" hint="" required outlined label="Username" />
            <q-input v-model="user.password" type="password" hint="" required outlined label="Password" />
            <q-select v-model="user.role" hint="" required outlined label="Rol" :options="roles" />
            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="showUpdateUserDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Agregar Usuario</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="userUpdate">
            <q-input v-model="user.name" hint="" required outlined label="Nombre" />
            <q-input v-model="user.email" hint="" required outlined label="Email" />
            <q-input v-model="user.username" hint="" required outlined label="Username" />
            <q-select v-model="user.role" hint="" required outlined label="Rol" :options="roles" />
            <q-btn :loading="loading" type="submit" color="primary" icon="add_circle_outline" label="Guardar" class="full-width" />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="permissionDialog" >
      <q-card style="width: 700px;max-width: 85vw">
        <q-card-section class="row items-center">
          <div class="text-h6">Modificar permisos</div>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="userUpdatePermission">
            <div class="row">
              <q-checkbox class="col-6" v-for="permission in permissions" :key="permission.id" v-model="permission.checked" :label="permission.name" />
            </div>
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
        'Consejo vigilancia',
        'Consejo administrativo',
        'Secretariaria',
        'Gerente',
      ],
      showAddUserDialog: false,
      showUpdateUserDialog: false,
      search: '',
      users: [],
      user:{},
      loading: false,
      userCrear: true,
      userColums:[
        {name: 'option', field: 'option', label: 'Opciones', align: 'left', sortable: true},
        {name: 'role', field: 'role', label: 'Rol', align: 'left', sortable: true},
        // {name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true},
        {name: 'name', label: 'Nombre', field: 'name', align: 'left', sortable: true},
        {name: 'permission', label: 'Permisos', field: 'permission', align: 'left', sortable: true},
        {name: 'email', label: 'Email', field: 'email', align: 'left', sortable: true},
      ],
      permissionDialog: false,
      permissions: [],
    }
  },
  methods:{
    userUpdatePermission () {
      this.loading = true
      const permissions = this.permissions.filter(permission => permission.checked)
      const permissionsUser = []
      permissions.forEach(permission => {
        permissionsUser.push(permission.name)
      })
      this.$api.post('attach', {
        user_id: this.user.id,
        permission: permissionsUser
      }).then(response => {
        this.usersGet()
        this.loading = false
        this.permissionDialog = false
        this.$q.notify({
          color: 'positive',
          message: 'Permisos actualizados',
          icon: 'check_circle'
        })
      }).catch(error => {
        this.loading = false
        this.$q.notify({
          color: 'negative',
          message: error.response.data.message,
          icon: 'warning'
        })
      })
    },
    updatePermission (user) {
      this.user = user
      this.permissionDialog = true
      this.permissions.forEach(permission => {
        permission.checked = false
        this.user.permissions.forEach(userPermission => {
          if (permission.id === userPermission.id) {
            permission.checked = true
          }
        })
      })
    },
    userUpdate(){
      this.loading = true
      this.$api.put(`user/${this.user.id}`,this.user)
        .then(res=>{
          this.loading = false
          this.showUpdateUserDialog = false
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'check_circle',
            message: 'Usuario actualizado'
          })
          this.user = {}
          this.usersGet()
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
    usersGet(){
      this.$q.loading.show()
      this.$api.get('user').then(response => {
        this.users = response.data
        this.$q.loading.hide()
      })
    },
    userCreate(){
      if (this.userCrear){
        this.loading = true
        this.$api.post('user', this.user).then(response => {
          this.usersGet()
          this.showAddUserDialog = false
          this.user = {}
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
        this.$api.put('user/'+this.user.id, this.user).then(response => {
          this.usersGet()
          this.showAddUserDialog = false
          this.user = {}
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
    userEdit(user){
      this.user = { ...user }
      this.userCrear = false
      this.showUpdateUserDialog = true
    },
    updatePassword(user){
      this.$q.dialog({
        title: 'Cambiar contraseña',
        message: 'Ingrese la nueva contraseña',
        prompt: {
          model: '',
          type: ''
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        this.$api.put(`updatePassword/${user.id}`,{password:data})
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
    userDelete(user){
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Está seguro de eliminar el usuario?',
        ok: 'Si',
        cancel: 'No'
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.delete('user/'+user.id).then(response => {
          this.usersGet()
        })
      })
    }
  },
  created() {
    this.$api.get('permissions').then(response => {
      this.permissions = response.data
    })
    this.usersGet()
  }
}
</script>

<style scoped>
</style>
