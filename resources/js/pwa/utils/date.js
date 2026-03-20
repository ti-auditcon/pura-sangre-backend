/**
 * Utilidades de fecha simples para la PWA.
 * Evita dependencias extra incompatibles con laravel-mix ^2.
 */

const DAYS = [
  'domingo',
  'lunes',
  'martes',
  'miércoles',
  'jueves',
  'viernes',
  'sábado'
];
const DAYS_SHORT = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];
const MONTHS = [
  'enero',
  'febrero',
  'marzo',
  'abril',
  'mayo',
  'junio',
  'julio',
  'agosto',
  'septiembre',
  'octubre',
  'noviembre',
  'diciembre'
];

/**
 * Parsea un string "YYYY-MM-DD" o "YYYY-MM-DD HH:mm:ss" a Date local.
 */
export function parseISO(str) {
  if (!str) return new Date();
  // Aseguramos que se interprete como local, no UTC
  const clean = str.replace(' ', 'T');
  const d = new Date(clean);
  // Fallback si el string es solo fecha
  if (str.length === 10) {
    const [y, m, day] = str.split('-').map(Number);
    return new Date(y, m - 1, day);
  }
  return d;
}

/**
 * Formato simple de fechas.
 * Tokens: YYYY MM DD dddd ddd D MMMM MMM HH mm ss
 * Tokens literales entre corchetes: [de]
 */
export function format(date, template) {
  if (!(date instanceof Date)) date = new Date(date);
  const y = date.getFullYear();
  const mo = date.getMonth();
  const d = date.getDate();
  const dow = date.getDay();
  const h = date.getHours();
  const mi = date.getMinutes();
  const s = date.getSeconds();

  return template
    .replace(/\[([^\]]+)\]/g, '\x00$1\x00')
    .replace(/YYYY/g, String(y))
    .replace(/MM/g, String(mo + 1).padStart(2, '0'))
    .replace(/DD/g, String(d).padStart(2, '0'))
    .replace(/D/g, String(d))
    .replace(/dddd/g, DAYS[dow])
    .replace(/ddd/g, DAYS_SHORT[dow])
    .replace(/MMMM/g, MONTHS[mo])
    .replace(/MMM/g, MONTHS[mo].substring(0, 3))
    .replace(/HH/g, String(h).padStart(2, '0'))
    .replace(/mm/g, String(mi).padStart(2, '0'))
    .replace(/ss/g, String(s).padStart(2, '0'))
    .replace(/\x00([^\x00]+)\x00/g, '$1');
}

/** Inicio de semana (lunes) para la fecha dada */
export function startOfWeek(date) {
  const d = new Date(date);
  const day = d.getDay();
  const diff = day === 0 ? -6 : 1 - day;
  d.setDate(d.getDate() + diff);
  d.setHours(0, 0, 0, 0);
  return d;
}

/** Fin de semana (domingo) */
export function endOfWeek(date) {
  const d = new Date(startOfWeek(date));
  d.setDate(d.getDate() + 6);
  d.setHours(23, 59, 59, 999);
  return d;
}

/** Suma N semanas a una fecha */
export function addWeeks(date, n) {
  const d = new Date(date);
  d.setDate(d.getDate() + n * 7);
  return d;
}

/** Comprueba si dos fechas caen en el mismo día */
export function isSameDay(a, b) {
  return (
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate()
  );
}
