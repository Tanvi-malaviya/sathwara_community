import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Axios Interceptors to show/hide global loader
window.axios.interceptors.request.use(config => {
    window.dispatchEvent(new CustomEvent('show-loader'));
    return config;
}, error => {
    window.dispatchEvent(new CustomEvent('hide-loader'));
    return Promise.reject(error);
});

window.axios.interceptors.response.use(response => {
    window.dispatchEvent(new CustomEvent('hide-loader'));
    return response;
}, error => {
    window.dispatchEvent(new CustomEvent('hide-loader'));
    return Promise.reject(error);
});

