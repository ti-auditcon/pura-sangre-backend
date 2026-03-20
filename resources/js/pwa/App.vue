<template>
  <div id="pwa-app">
    <router-view />
    <BottomNav v-if="isLoggedIn" />
  </div>
</template>

<script>
import BottomNav from './components/BottomNav.vue';

export default {
  name: 'App',
  components: { BottomNav },
  computed: {
    isLoggedIn() {
      return !!localStorage.getItem('pwa_token');
    }
  }
};
</script>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  background: #f5f5f5;
  color: #1a1a1a;
}
#pwa-app {
  min-height: 100vh;
  padding-bottom: 64px;
}
.page {
  max-width: 480px;
  margin: 0 auto;
  padding: 16px;
}
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: opacity 0.15s;
}
.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.btn-primary {
  background: #e53935;
  color: #fff;
  width: 100%;
}
.btn-outline {
  background: transparent;
  border: 2px solid #e53935;
  color: #e53935;
  width: 100%;
}
.card {
  background: #fff;
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
  margin-bottom: 12px;
}
.error-msg {
  color: #e53935;
  font-size: 13px;
  margin-top: 8px;
  text-align: center;
}
.loading {
  text-align: center;
  padding: 40px 16px;
  color: #888;
}
</style>
