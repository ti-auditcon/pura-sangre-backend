<template>
  <div id="pwa-app" :class="{ 'has-nav': loggedIn }">
    <router-view />
    <BottomNav v-if="loggedIn" />
  </div>
</template>

<script>
import BottomNav from './components/BottomNav.vue';

export default {
  name: 'App',
  components: { BottomNav },
  data() {
    return {
      loggedIn: false
    };
  },
  created() {
    this.loggedIn =
      !!localStorage.getItem('pwa_token') && this.$route.name !== 'login';
  },
  watch: {
    $route() {
      this.loggedIn =
        !!localStorage.getItem('pwa_token') && this.$route.name !== 'login';
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
  background: linear-gradient(135deg, #26c6da 0%, #0097a7 100%);
  color: #fff;
  width: 100%;
  box-shadow: 0 2px 8px rgba(0, 151, 167, 0.35);
}
.btn-outline {
  background: transparent;
  border: 2px solid #00bcd4;
  color: #0097a7;
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
  color: #d32f2f;
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
