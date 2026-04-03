<template>
  <div class="clases-root">
    <!-- Header semana: sticky wrapper con fondo gris que cubre todo -->
    <div class="week-nav-wrap">
      <div class="week-nav">
        <span class="week-label">{{ weekLabel }}</span>
      </div>
    </div>

    <!-- Lista de días -->
    <div class="clases-body">
      <div v-if="loading" class="loading">Cargando clases…</div>
      <div v-else-if="error" class="error-msg">{{ error }}</div>
      <div v-else>
        <div v-if="groupedClases.length === 0" class="empty">
          No hay clases esta semana.
        </div>
        <div v-for="day in groupedClases" :key="day.date">
          <h3 class="day-heading">{{ day.label }}</h3>
          <ClaseCard
            v-for="clase in day.clases"
            :key="clase.id"
            :clase="clase"
            @click.native="goToDetail(clase.id)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { clases } from '../api';
import ClaseCard from '../components/ClaseCard.vue';
import { format, parseISO, isSameDay } from '../utils/date';

export default {
  name: 'Clases',
  components: { ClaseCard },
  data() {
    return {
      clasesList: [],
      loading: false,
      error: null,
      lastLoaded: null
    };
  },
  computed: {
    weekStart() {
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      return today;
    },
    weekEnd() {
      const end = new Date(this.weekStart);
      end.setDate(end.getDate() + 6);
      end.setHours(23, 59, 59, 999);
      return end;
    },
    weekLabel() {
      const s = format(this.weekStart, 'D [de] MMMM');
      const e = format(this.weekEnd, 'D [de] MMMM [de] YYYY');
      return `${s} – ${e}`;
    },
    groupedClases() {
      const days = [];
      let current = new Date(this.weekStart);
      while (current <= this.weekEnd) {
        const dayClases = this.clasesList.filter(c =>
          isSameDay(parseISO(c.date), current)
        );
        if (dayClases.length) {
          days.push({
            date: format(current, 'YYYY-MM-DD'),
            label: format(current, 'dddd D [de] MMMM'),
            clases: dayClases
          });
        }
        current.setDate(current.getDate() + 1);
      }
      return days;
    }
  },
  mounted() {
    this.load();
  },
  activated() {
    // keep-alive: solo recargar si han pasado más de 60 segundos desde la última carga
    const STALE_MS = 60000;
    if (!this.lastLoaded || Date.now() - this.lastLoaded > STALE_MS) {
      this.load();
    }
  },
  methods: {
    async load() {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await clases.list(
          format(this.weekStart, 'YYYY-MM-DD'),
          format(this.weekEnd, 'YYYY-MM-DD')
        );
        this.clasesList = data;
        this.lastLoaded = Date.now();
      } catch (e) {
        this.error = 'No se pudieron cargar las clases.';
      } finally {
        this.loading = false;
      }
    },
    goToDetail(id) {
      this.$router.push(`/clases/${id}`);
    }
  }
};
</script>

<style scoped>
/* Reemplaza .page: mismo max-width y centrado, pero SIN padding-top */
.clases-root {
  max-width: 480px;
  margin: 0 auto;
}
/* El wrapper sticky empieza en y=0 del contenedor, así nunca se mueve */
.week-nav-wrap {
  position: sticky;
  top: 0;
  z-index: 20;
  background: #f5f5f5;
  padding: 16px 16px 12px;
}
/* El pill flotante vive dentro del wrapper */
.week-nav {
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #26c6da 0%, #0097a7 100%);
  border-radius: 12px;
  padding: 12px 16px;
  box-shadow: 0 2px 8px rgba(0, 151, 167, 0.3);
}
.week-label {
  font-weight: 600;
  font-size: 14px;
  text-transform: capitalize;
  color: #fff;
}
/* Contenido con el padding lateral equivalente a .page */
.clases-body {
  padding: 0 16px 80px;
}
/* Sticky justo debajo del wrap: 16px(top) + 44px(nav) + 8px(bottom) = 68px */
.day-heading {
  position: sticky;
  top: 63px;
  z-index: 10;
  font-size: 15px;
  font-weight: 700;
  color: #0097a7;
  text-transform: capitalize;
  margin: 0 -16px 8px;
  padding: 6px 16px 4px;
  letter-spacing: 0.3px;
  background: #f5f5f5;
  border-bottom: 2px solid #e0f7fa;
}
.empty {
  text-align: center;
  padding: 40px 0;
  color: #aaa;
}
</style>
