import axios from 'axios';
import { useUser } from './stores/user';

const instance = axios.create({
  baseURL: 'http://localhost:8000/',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
}
});

instance.interceptors.request.use(

    (config) => {
        const userStore = useUser();
        const token = userStore.token;
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
      return Promise.reject(error);
    }
);

instance.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    if (error.message === 'Network Error') {
      // Manejar error de red aquí
    }

    return Promise.reject(error);
  }
);

export default instance
