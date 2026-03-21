<template>
  <nav class="bottom-nav">
    <router-link to="/clases" class="nav-item" active-class="active">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
        width="22"
        height="22"
      >
        <path
          d="M19 4h-1V2h-2v2H8V2H6v2H5C3.9 4 3 4.9 3 6v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zm0-13H5V6h14v1z"
        />
      </svg>
      <span>Clases</span>
    </router-link>
    <router-link to="/mis-reservas" class="nav-item" active-class="active">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
        width="22"
        height="22"
      >
        <path
          d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"
        />
      </svg>
      <span>Mis reservas</span>
    </router-link>
    <button class="nav-item" @click="logout">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
        width="22"
        height="22"
      >
        <path
          d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"
        />
      </svg>
      <span>Salir</span>
    </button>
  </nav>
</template>

<script>
import { auth } from '../api';

export default {
  name: 'BottomNav',
  methods: {
    async logout() {
      try {
        await auth.logout();
      } catch (e) {}
      localStorage.removeItem('pwa_token');
      localStorage.removeItem('pwa_user');
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped>
.bottom-nav {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  height: 62px;
  background: linear-gradient(to right, #26c6da, #0097a7);
  display: flex;
  justify-content: space-around;
  align-items: center;
  z-index: 100;
  box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.08), 0 -4px 16px rgba(0, 151, 167, 0.2);
}
.nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
  color: rgba(255, 255, 255, 0.75);
  text-decoration: none;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.2px;
  padding: 8px 16px;
  background: none;
  border: none;
  cursor: pointer;
  transition: color 0.15s;
  flex: 1;
  position: relative;
}
.nav-item.active {
  color: #fff;
}
.nav-item.active::after {
  content: '';
  position: absolute;
  bottom: 6px;
  width: 20px;
  height: 2px;
  background: #fff;
  border-radius: 2px;
}
.nav-item:hover {
  color: #fff;
}
</style>
