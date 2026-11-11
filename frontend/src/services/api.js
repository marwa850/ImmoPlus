import axios from 'axios';

// Adresse de l'API Laravel
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: { Accept: 'application/json' },
});

// Avant chaque requête, on ajoute le token (s'il existe) dans l'en-tête
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;