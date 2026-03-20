<template>
  <div class="page">
    <h2 class="page-title">Mis próximas reservas</h2>

    <div v-if="loading" class="loading">Cargando reservas…</div>
    <div v-else-if="error" class="error-msg">{{ error }}</div>
    <div v-else-if="list.length === 0" class="empty">
      <p>No tienes reservas próximas.</p>
      <router-link
        to="/clases"
        class="btn btn-primary"
        style="display:inline-flex;margin-top:16px;width:auto;padding:10px 24px"
      >
        Ver clases
      </router-link>
    </div>
    <div v-else>
      <div
        v-for="res in list"
        :key="res.id"
        class="card reservation-card"
        @click="$router.push(`/clases/${res.clase.id}`)"
      >
        <div
          class="res-bar"
          :style="{
            background:
              (res.clase.clase_type && res.clase.clase_type.clase_color) ||
              '#00BCD4'
          }"
        ></div>
        <div class="res-body">
          <p class="res-type">
            {{ res.clase.clase_type && res.clase.clase_type.clase_type }}
          </p>
          <p class="res-date">{{ res.clase.date | date }}</p>
          <p class="res-time">
            {{ res.clase.start_at | time }} – {{ res.clase.finish_at | time }}
          </p>
          <p v-if="res.clase.coach" class="res-coach">
            Coach: {{ res.clase.coach.first_name }}
            {{ res.clase.coach.last_name }}
          </p>
        </div>
        <div class="res-right">
          <span
            class="status-badge"
            :class="statusClass(res.reservation_status_id)"
          >
            {{
              res.reservation_status &&
                res.reservation_status.reservation_status
            }}
          </span>
          <button
            class="cancel-btn"
            :disabled="cancelling === res.id"
            @click.stop="cancel(res)"
          >
            {{ cancelling === res.id ? '…' : 'Cancelar' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { reservations } from '../api';
import { format, parseISO } from '../utils/date';

export default {
  name: 'MisReservas',
  filters: {
    time(val) {
      return val ? val.substring(0, 5) : '';
    },
    date(val) {
      if (!val) return '';
      return format(parseISO(val), 'ddd D [de] MMMM');
    }
  },
  data() {
    return {
      list: [],
      loading: false,
      error: null,
      cancelling: null
    };
  },
  mounted() {
    this.load();
  },
  methods: {
    async load() {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await reservations.list();
        this.list = data;
      } catch (e) {
        this.error = 'No se pudieron cargar las reservas.';
      } finally {
        this.loading = false;
      }
    },
    async cancel(res) {
      this.cancelling = res.id;
      try {
        await reservations.cancel(res.id);
        this.list = this.list.filter(r => r.id !== res.id);
      } catch (err) {
        alert(
          (err.response && err.response.data && err.response.data.error) ||
            'No se pudo cancelar la reserva.'
        );
      } finally {
        this.cancelling = null;
      }
    },
    statusClass(statusId) {
      const map = { 1: 'pending', 2: 'confirmed', 3: 'consumed', 4: 'lost' };
      return map[statusId] || 'pending';
    }
  }
};
</script>

<style scoped>
.page-title {
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 20px;
  color: #0097a7;
}
.reservation-card {
  display: flex;
  align-items: stretch;
  overflow: hidden;
  cursor: pointer;
}
.res-bar {
  width: 6px;
  flex-shrink: 0;
}
.res-body {
  flex: 1;
  padding: 14px 12px;
}
.res-type {
  font-weight: 700;
  font-size: 15px;
  margin-bottom: 3px;
}
.res-date {
  font-size: 13px;
  color: #555;
  text-transform: capitalize;
}
.res-time {
  font-size: 14px;
  font-weight: 600;
  margin-top: 2px;
}
.res-coach {
  font-size: 12px;
  color: #888;
  margin-top: 3px;
}
.res-right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: space-between;
  padding: 14px 14px 14px 0;
  gap: 8px;
}
.status-badge {
  font-size: 10px;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 20px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.status-badge.pending {
  background: #fff8e1;
  color: #f57f17;
}
.status-badge.confirmed {
  background: #e0f7fa;
  color: #0097a7;
}
.status-badge.consumed {
  background: #e3f2fd;
  color: #1565c0;
}
.status-badge.lost {
  background: #fce4e4;
  color: #b71c1c;
}
.cancel-btn {
  font-size: 12px;
  font-weight: 600;
  color: #d32f2f;
  background: none;
  border: 1px solid #ef9a9a;
  border-radius: 6px;
  padding: 4px 10px;
  cursor: pointer;
}
.cancel-btn:disabled {
  opacity: 0.5;
}
.empty {
  text-align: center;
  padding: 40px 0;
  color: #aaa;
}
</style>
