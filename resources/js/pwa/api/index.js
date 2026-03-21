import client from './client';

export const auth = {
  login: (email, password) => client.post('/auth/login', { email, password }),
  logout: () => client.post('/auth/logout'),
  me: () => client.get('/me')
};

export const clases = {
  list: (start, end) => client.get('/clases', { params: { start, end } }),
  show: id => client.get(`/clases/${id}`)
};

export const reservations = {
  list: () => client.get('/reservations'),
  create: clase_id => client.post('/reservations', { clase_id }),
  confirm: id => client.put(`/reservations/${id}/confirm`),
  cancel: id => client.delete(`/reservations/${id}`)
};
