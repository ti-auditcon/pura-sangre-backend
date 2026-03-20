<template>
  <div class="page">
    <button class="back-btn" @click="$router.back()">‹ Volver</button>

    <div v-if="loading" class="loading">Cargando clase…</div>
    <div v-else-if="error" class="error-msg">{{ error }}</div>
    <template v-else-if="clase">
      <!-- Header clase -->
      <div
        class="clase-header card"
        :style="{
          borderTop: `6px solid ${(clase.clase_type &&
            clase.clase_type.clase_color) ||
            '#00BCD4'}`
        }"
      >
        <h2 class="clase-title">
          {{ clase.clase_type && clase.clase_type.clase_type }}
        </h2>
        <p class="clase-date">{{ clase.date | date }}</p>
        <p class="clase-time">
          {{ clase.start_at | time }} – {{ clase.finish_at | time }}
        </p>
        <p v-if="clase.coach" class="clase-coach">
          Coach:
          <strong
            >{{ clase.coach.first_name }} {{ clase.coach.last_name }}</strong
          >
        </p>
        <p v-if="clase.room" class="clase-room">Sala: {{ clase.room }}</p>
        <div class="quota-info" :class="{ full: isFull }">
          <span>{{
            isFull
              ? 'Clase llena'
              : `${clase.reservations_count}/${clase.quota} reservas`
          }}</span>
        </div>
      </div>

      <!-- Acción reserva -->
      <div class="action-card card">
        <template v-if="myReservation">
          <p class="reserved-status">
            ✓ Tienes esta clase reservada
            <span class="status-badge">{{
              myReservation.reservation_status &&
                myReservation.reservation_status.reservation_status
            }}</span>
          </p>
          <button
            class="btn btn-outline"
            :disabled="cancelling"
            @click="cancelReservation"
          >
            {{ cancelling ? 'Cancelando…' : 'Cancelar reserva' }}
          </button>
        </template>
        <template v-else>
          <p v-if="isFull" class="full-msg">
            Esta clase está llena, no hay cupos disponibles.
          </p>
          <button
            v-else
            class="btn btn-primary"
            :disabled="booking"
            @click="makeReservation"
          >
            {{ booking ? 'Reservando…' : 'Reservar clase' }}
          </button>
        </template>
        <p v-if="actionError" class="error-msg">{{ actionError }}</p>
      </div>

      <!-- Lista de alumnos inscritos -->
      <div class="students-card card" v-if="students.length > 0">
        <h3 class="students-title">
          Alumnos inscritos ({{ students.length }})
        </h3>
        <ul class="students-list">
          <li
            v-for="(student, index) in students"
            :key="index"
            class="student-item"
          >
            <img
              v-if="student.avatar"
              :src="student.avatar"
              :alt="student.name"
              class="student-avatar"
              loading="lazy"
            />
            <span v-else class="student-avatar-placeholder">{{
              initials(student.name)
            }}</span>
            <span class="student-name">{{ student.name }}</span>
            <span
              class="student-status-dot"
              :class="
                student.reservation_status_id === 2 ? 'confirmed' :
                student.reservation_status_id === 3 ? 'consumed' : 'pending'
              "
              :title="
                student.reservation_status_id === 2 ? 'Confirmado' :
                student.reservation_status_id === 3 ? 'Asistió' : 'Pendiente'
              "
            ></span>
          </li>
        </ul>
      </div>
      <div class="students-card card" v-else-if="!loading">
        <p class="no-students">Aún no hay alumnos inscritos.</p>
      </div>
    </template>
  </div>
</template>

<script>
import { clases, reservations } from '../api';
import { format, parseISO } from '../utils/date';

export default {
  name: 'ClaseDetail',
  filters: {
    time(val) {
      return val ? val.substring(0, 5) : '';
    },
    date(val) {
      if (!val) return '';
      return format(parseISO(val), 'dddd D [de] MMMM [de] YYYY');
    }
  },
  data() {
    return {
      clase: null,
      isFull: false,
      myReservation: null,
      students: [],
      loading: false,
      error: null,
      booking: false,
      cancelling: false,
      actionError: null
    };
  },
  mounted() {
    this.load();
  },
  watch: {
    '$route.params.id': function(newId, oldId) {
      if (newId !== oldId) {
        this.clase = null;
        this.students = [];
        this.myReservation = null;
        this.isFull = false;
        this.actionError = null;
        this.load();
      }
    }
  },
  methods: {
    async load() {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await clases.show(this.$route.params.id);
        this.clase = data.clase;
        this.isFull = data.is_full;
        this.myReservation = data.my_reservation;
        this.students = data.students || [];
      } catch (e) {
        this.error = 'No se pudo cargar la clase.';
      } finally {
        this.loading = false;
      }
    },
    async makeReservation() {
      this.booking = true;
      this.actionError = null;
      try {
        await reservations.create(this.clase.id);
        await this.load();
      } catch (err) {
        this.actionError =
          (err.response && err.response.data && err.response.data.error) ||
          'No se pudo reservar.';
      } finally {
        this.booking = false;
      }
    },
    initials(name) {
      return name
        .split(' ')
        .slice(0, 2)
        .map(function(w) {
          return w[0];
        })
        .join('')
        .toUpperCase();
    },
    async cancelReservation() {
      this.cancelling = true;
      this.actionError = null;
      try {
        await reservations.cancel(this.myReservation.id);
        await this.load();
      } catch (err) {
        this.actionError =
          (err.response && err.response.data && err.response.data.error) ||
          'No se pudo cancelar.';
      } finally {
        this.cancelling = false;
      }
    }
  }
};
</script>

<style scoped>
.back-btn {
  background: none;
  border: none;
  color: #0097a7;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  margin-bottom: 16px;
}
.clase-header {
  margin-bottom: 16px;
}
.clase-title {
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 8px;
}
.clase-date {
  font-size: 14px;
  color: #555;
  text-transform: capitalize;
  margin-bottom: 2px;
}
.clase-time {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 4px;
}
.clase-coach,
.clase-room {
  font-size: 13px;
  color: #888;
  margin-top: 4px;
}
.quota-info {
  display: inline-block;
  margin-top: 10px;
  font-size: 12px;
  font-weight: 600;
  background: #f0f0f0;
  color: #555;
  padding: 3px 10px;
  border-radius: 20px;
}
.quota-info.full {
  background: #fce4e4;
  color: #d32f2f;
}
.action-card {
  text-align: center;
}
.reserved-status {
  font-size: 15px;
  color: #0097a7;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
}
.status-badge {
  font-size: 11px;
  background: #e8f5e9;
  color: #2e7d32;
  padding: 2px 8px;
  border-radius: 20px;
  font-weight: 600;
}
.full-msg {
  color: #d32f2f;
  font-size: 14px;
  margin-bottom: 8px;
}
.students-card {
  margin-top: 16px;
}
.students-title {
  font-size: 15px;
  font-weight: 700;
  margin-bottom: 12px;
  color: #333;
}
.students-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.student-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  border-bottom: 1px solid #f0f0f0;
}
.student-item:last-child {
  border-bottom: none;
}
.student-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  flex-shrink: 0;
}
.student-avatar-placeholder {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: #e0e0e0;
  color: #757575;
  font-size: 13px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.student-name {
  flex: 1;
  font-size: 14px;
  color: #333;
}
.student-status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}
.student-status-dot.confirmed {
  background: #43a047;
}
.student-status-dot.consumed {
  background: #1565c0;
}
.student-status-dot.pending {
  background: #fb8c00;
}
.no-students {
  font-size: 13px;
  color: #aaa;
  text-align: center;
  margin: 0;
}
</style>
