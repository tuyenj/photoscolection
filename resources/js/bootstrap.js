import {getCookieValue} from './util'
import axios from 'axios';

// Ajax 設定
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.interceptors.response.use(
    // 正常レスポンス
    response => response,
    // エラーレスポンス
    error => error.response || error,
);

axios.interceptors.request.use(
    config => {
        // クッキーからトークンを取り出してヘッダーに添付する
        config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN');
        return config;
    }
);

window.axios = axios;
