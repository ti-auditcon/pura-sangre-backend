import axios from 'axios';

const client = axios.create({
  baseURL: '/api/pwa',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json'
  }
});

client.interceptors.request.use(config => {
  const token = localStorage.getItem('pwa_token');
  if (token) {
    config.headers['Authorization'] = `Bearer ${token}`;
  }
  return config;
});

client.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('pwa_token');
      localStorage.removeItem('pwa_user');
      window.location.href = '/clases-app';
    }
    return Promise.reject(error);
  }
);

export default client;
