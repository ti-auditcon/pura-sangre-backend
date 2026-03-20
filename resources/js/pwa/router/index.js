import Vue from 'vue';
import VueRouter from 'vue-router';
import Login from '../views/Login.vue';
import Clases from '../views/Clases.vue';
import ClaseDetail from '../views/ClaseDetail.vue';
import MisReservas from '../views/MisReservas.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    redirect: () => {
      return localStorage.getItem('pwa_token') ? '/clases' : '/login';
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { public: true }
  },
  {
    path: '/clases',
    name: 'clases',
    component: Clases,
    meta: { requiresAuth: true }
  },
  {
    path: '/clases/:id',
    name: 'clase-detail',
    component: ClaseDetail,
    meta: { requiresAuth: true }
  },
  {
    path: '/mis-reservas',
    name: 'mis-reservas',
    component: MisReservas,
    meta: { requiresAuth: true }
  },
  { path: '*', redirect: '/' }
];

const router = new VueRouter({
  mode: 'history',
  base: '/clases-app/',
  routes
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = !!localStorage.getItem('pwa_token');
  if (to.meta.requiresAuth && !isLoggedIn) {
    return next({ name: 'login' });
  }
  if (to.name === 'login' && isLoggedIn) {
    return next({ name: 'clases' });
  }
  next();
});

export default router;
