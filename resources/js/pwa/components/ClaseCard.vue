<template>
  <div class="card clase-card">
    <div
      class="clase-left"
      :style="{
        borderColor:
          (clase.clase_type && clase.clase_type.clase_color) || '#E53935'
      }"
    >
      <span class="time">{{ clase.start_at | time }}</span>
      <span class="time-end">{{ clase.finish_at | time }}</span>
    </div>
    <div class="clase-body">
      <p class="clase-name">
        {{ (clase.clase_type && clase.clase_type.clase_type) || '—' }}
      </p>
      <p class="clase-meta">
        <span v-if="clase.coach"
          >{{ clase.coach.first_name }} {{ clase.coach.last_name }}</span
        >
        <span v-if="clase.room"> · Sala {{ clase.room }}</span>
      </p>
      <div class="clase-footer">
        <span class="quota-badge" :class="{ full: clase.is_full }">
          {{
            clase.is_full
              ? 'Llena'
              : `${clase.reservations_count}/${clase.quota}`
          }}
        </span>
        <span v-if="clase.my_reservation_id" class="reserved-badge"
          >Reservada ✓</span
        >
      </div>
    </div>
    <div class="clase-arrow">›</div>
  </div>
</template>

<script>
export default {
  name: 'ClaseCard',
  props: {
    clase: { type: Object, required: true }
  },
  filters: {
    time(val) {
      if (!val) return '';
      return val.substring(0, 5);
    }
  }
};
</script>

<style scoped>
.clase-card {
  display: flex;
  align-items: center;
  cursor: pointer;
  gap: 12px;
  transition: box-shadow 0.15s;
}
.clase-card:hover {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
}
.clase-left {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 48px;
  border-left: 4px solid #e53935;
  padding-left: 8px;
}
.time {
  font-weight: 700;
  font-size: 15px;
  color: #1a1a1a;
}
.time-end {
  font-size: 12px;
  color: #999;
}
.clase-body {
  flex: 1;
}
.clase-name {
  font-weight: 600;
  font-size: 15px;
  margin-bottom: 3px;
}
.clase-meta {
  font-size: 12px;
  color: #888;
  margin-bottom: 6px;
}
.clase-footer {
  display: flex;
  gap: 8px;
  align-items: center;
}
.quota-badge {
  font-size: 11px;
  font-weight: 600;
  background: #f0f0f0;
  color: #555;
  padding: 2px 8px;
  border-radius: 20px;
}
.quota-badge.full {
  background: #fce4e4;
  color: #e53935;
}
.reserved-badge {
  font-size: 11px;
  font-weight: 600;
  background: #e8f5e9;
  color: #2e7d32;
  padding: 2px 8px;
  border-radius: 20px;
}
.clase-arrow {
  color: #ccc;
  font-size: 20px;
  padding-left: 4px;
}
</style>
