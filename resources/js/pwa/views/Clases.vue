<template>
  <div class="page">
    <!-- Header semana -->
    <div class="week-nav">
      <span class="week-label">{{ weekLabel }}</span>
    </div>

    <!-- Lista de días -->
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
      error: null
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
      const s = format(this.weekStart, 'D MMM');
      const e = format(this.weekEnd, 'D MMM YYYY');
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
.week-nav {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  background: #fff;
  border-radius: 12px;
  padding: 12px 16px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
}
.week-label {
  font-weight: 600;
  font-size: 14px;
  text-transform: capitalize;
}
.day-heading {
  font-size: 13px;
  font-weight: 700;
  color: #888;
  text-transform: capitalize;
  margin: 16px 0 8px;
  letter-spacing: 0.5px;
}
.empty {
  text-align: center;
  padding: 40px 0;
  color: #aaa;
}
</style>
