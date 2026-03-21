<template>
  <div class="login-page">
    <div class="login-box">
      <div class="logo">
        <img
          src="/img/logo.png"
          alt="Pura Sangre"
          onerror="this.style.display='none'"
        />
        <h1>Pura Sangre</h1>
        <p>Reserva de clases</p>
      </div>

      <form @submit.prevent="submit" novalidate>
        <div class="field">
          <label>Correo electrónico</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="tu@correo.cl"
            autocomplete="email"
            required
          />
        </div>
        <div class="field">
          <label>Contraseña</label>
          <input
            v-model="form.password"
            type="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
          />
        </div>
        <p v-if="error" class="error-msg">{{ error }}</p>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          {{ loading ? 'Ingresando…' : 'Ingresar' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { auth } from '../api';

export default {
  name: 'Login',
  data() {
    return {
      form: { email: '', password: '' },
      loading: false,
      error: null
    };
  },
  methods: {
    async submit() {
      this.error = null;
      this.loading = true;
      try {
        const { data } = await auth.login(this.form.email, this.form.password);
        localStorage.setItem('pwa_token', data.token);
        localStorage.setItem('pwa_user', JSON.stringify(data.user));
        this.$router.push('/clases');
      } catch (err) {
        this.error =
          (err.response && err.response.data && err.response.data.error) ||
          'Credenciales incorrectas.';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.login-page {
  height: 100dvh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(145deg, #004d60 0%, #26c6da 100%);
  padding: 24px;
  overflow: hidden;
  box-sizing: border-box;
}
.login-box {
  background: #fff;
  border-radius: 16px;
  padding: 32px 24px;
  width: 100%;
  max-width: 380px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
}
.logo {
  text-align: center;
  margin-bottom: 28px;
}
.logo img {
  height: 64px;
  margin-bottom: 8px;
}
.logo h1 {
  font-size: 22px;
  font-weight: 700;
  color: #1a1a1a;
}
.logo p {
  color: #888;
  font-size: 14px;
}
.field {
  margin-bottom: 16px;
}
.field label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #555;
  margin-bottom: 6px;
}
.field input {
  width: 100%;
  padding: 12px 14px;
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  font-size: 15px;
  outline: none;
  transition: border-color 0.2s;
}
.field input:focus {
  border-color: #00bcd4;
}
</style>
