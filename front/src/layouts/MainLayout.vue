<template>
  <q-layout view="lHh Lpr lFf" class="bg-grey-2">
    <q-header class="bg-grey-2">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          color="primary"
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title class="text-bold text-primary">
          <!--          <q-item v-if="store.userChat.avatar!=undefined">-->
          <!--            <q-item-section avatar>-->
          <!--              <q-avatar>-->
          <!--                <img  :src="store.userChat.avatar">-->
          <!--              </q-avatar>-->
          <!--            </q-item-section>-->
          <!--            <q-item-section>-->
          <!--              <q-item-label lines="1">-->
          <!--                {{ store.userChat.name }}-->
          <!--              </q-item-label>-->
          <!--              <q-item-label class="conversation__summary" caption>-->
          <!--                En linea {{ store.userChat.fechaConexion }}-->
          <!--              </q-item-label>-->
          <!--            </q-item-section>-->
          <!--          </q-item>-->
        </q-toolbar-title>
        <div class="row q-pt-xs">
          <!--          <div class="col-8">-->
          <!--&lt;!&ndash;            <q-input dense rounded outlined v-model="search" placeholder="Buscar">&ndash;&gt;-->
          <!--&lt;!&ndash;              <template v-slot:prepend>&ndash;&gt;-->
          <!--&lt;!&ndash;                <q-icon name="search" />&ndash;&gt;-->
          <!--&lt;!&ndash;              </template>&ndash;&gt;-->
          <!--&lt;!&ndash;            </q-input>&ndash;&gt;-->
          <!--          </div>-->
          <!--          <div class="col-2 flex flex-center">-->
          <!--&lt;!&ndash;            <q-btn&ndash;&gt;-->
          <!--&lt;!&ndash;              flat&ndash;&gt;-->
          <!--&lt;!&ndash;              dense&ndash;&gt;-->
          <!--&lt;!&ndash;              round&ndash;&gt;-->
          <!--&lt;!&ndash;              color="primary"&ndash;&gt;-->
          <!--&lt;!&ndash;              icon="o_notifications"&ndash;&gt;-->
          <!--&lt;!&ndash;              aria-label="Notifications">&ndash;&gt;-->
          <!--&lt;!&ndash;              <q-badge color="primary" floating transparent>&ndash;&gt;-->
          <!--&lt;!&ndash;                4&ndash;&gt;-->
          <!--&lt;!&ndash;              </q-badge>&ndash;&gt;-->
          <!--&lt;!&ndash;            </q-btn>&ndash;&gt;-->
          <!--          </div>-->
          <div class="col-12 flex flex-center">
            <q-btn outline color="primary" no-caps>
              <q-avatar size="38px">
                <q-icon name="account_circle" size="38px" color="primary"/>
                <!--                <img v-if="store.user.avatar!=undefined" :src="url+'../imagenes/'+store.user.avatar">-->
              </q-avatar>
              <div>
                {{ store.user.name }}
              </div>
              <q-menu>
                <q-list style="min-width: 100px">
                  <q-item clickable v-ripple>
                    <q-item-section avatar>
                      <q-icon color="primary" name="perm_contact_calendar"/>
                    </q-item-section>
                    <q-item-section>{{ store.user.name }}</q-item-section>
                  </q-item>
                  <q-separator/>
                  <q-item clickable v-ripple @click="logout">
                    <q-item-section avatar>
                      <q-icon color="primary" name="logout"/>
                    </q-item-section>
                    <q-item-section>Salir</q-item-section>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>
          </div>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      :width="180"
    >
      <q-list>
        <q-item-label class="q-pa-xs">
          <div class="row">
            <div class="col-3 flex flex-center">
              <!--              <q-avatar size="48px">-->
              <q-icon color="primary" size="48px" name="o_schema"/>
              <!--              </q-avatar>-->
            </div>
            <div class="col-9">
              <div class="text-h6 text-bold "> {{ store.user.name }}</div>
              <div class="text-caption">
                <b>Bienvenido</b>
                <!--                <q-badge :label="store.user.role" />-->
              </div>
            </div>
          </div>
        </q-item-label>
        <q-separator/>
        <q-toolbar class="bg-primary text-white shadow-2">
          <q-toolbar-title>Opciones</q-toolbar-title>
        </q-toolbar>
        <q-list bordered>
          <q-list>
            <q-item clickable exact to="/" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_home"/>
              </q-item-section>
              <q-item-section>
                Principal
              </q-item-section>
            </q-item>
            <q-item
              v-if="store.permissions.includes('tornaguia create')||store.permissions.includes('tornaguia read')||store.permissions.includes('tornaguia update')||store.permissions.includes('tornaguia delete')"
              clickable exact to="tornaguia" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_description"/>
              </q-item-section>
              <q-item-section>
                Tornaguias
              </q-item-section>
            </q-item>
            <q-item
              v-if="store.permissions.includes('transporte create')||store.permissions.includes('transporte read')||store.permissions.includes('transporte update')||store.permissions.includes('transporte delete')"
              clickable exact to="transporte" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_directions_bus_filled"/>
              </q-item-section>
              <q-item-section>
                Transporte
              </q-item-section>
            </q-item>
            <q-item
              v-if="store.permissions.includes('contratista create')||store.permissions.includes('contratista read')||store.permissions.includes('contratista update')||store.permissions.includes('contratista delete')"
              clickable exact to="contratista" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="work_outline"/>
              </q-item-section>
              <q-item-section>
                Contratista
              </q-item-section>
            </q-item>
            <q-item
              v-if="store.permissions.includes('empresa create')||store.permissions.includes('empresa read')||store.permissions.includes('empresa update')||store.permissions.includes('empresa delete')"
              clickable exact to="empresa" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_business"/>
              </q-item-section>
              <q-item-section>
                Empresa
              </q-item-section>
            </q-item>
            <q-item
              v-if="store.permissions.includes('conductor create')||store.permissions.includes('conductor read')||store.permissions.includes('conductor update')||store.permissions.includes('conductor delete')"
              clickable exact to="driver" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_legend_toggle"/>
              </q-item-section>
              <q-item-section>
                Conductores
              </q-item-section>
            </q-item>
            <q-item
              v-if="store.permissions.includes('user create')||store.permissions.includes('user read')||store.permissions.includes('user update')||store.permissions.includes('user delete')"
              clickable exact to="user" active-class="bg-blue-grey-6 text-white">
              <q-item-section avatar>
                <q-icon name="o_people"/>
              </q-item-section>
              <q-item-section>
                Usuarios
              </q-item-section>
            </q-item>
          </q-list>
          <q-separator/>
        </q-list>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view/>
    </q-page-container>

  </q-layout>
</template>

<script>
import {useCounterStore} from "stores/example-store";
import {date} from "quasar";

export default {
  name: 'MainLayout',
  data() {
    return {
      loading: false,
      url: process.env.API,
      leftDrawerOpen: false,
      search: '',
      store: useCounterStore(),
    }
  },
  created() {
  },
  methods: {
    logout() {
      this.$q.dialog({
        message: '¿Quieres cerrar sesión?',
        title: "Salir",
        ok: {
          push: true
        },
        cancel: {
          push: true,
          color: 'negative'
        }
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.post('logout').then(res => {
          this.$api.defaults.headers.common['Authorization'] = ''
          this.store.user = {}
          this.store.negocio = {}
          this.store.userChat = {}
          localStorage.removeItem('tokenJor')
          this.store.isLogged = false
          this.$router.push('/login')
          this.$q.loading.hide()
        })
      })
    },
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen
    }
  }
}
</script>
