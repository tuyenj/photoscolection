/**
 * クッキーの値を取得する
 * @param {String} searchKey 検索するキー
 * @returns {String} キーに対応する値
 */
export function getCookieValue (searchKey) {
    if (typeof searchKey === 'undefined') {
        return '';
    }

    let val = '';

    document.cookie.split(';').forEach(cookie => {
        const [key, value] = cookie.split('=');
        if (key === searchKey) {
            return val = value;
        }
    });

    return val;
}
/**
 * パラメーターから指定の値を取得する
 * @param {Object} params
 * @param {string} paramName
 * @param {Object} defaultValue
 * @returns {Object}
 */
export function findParameter(params, paramName, defaultValue = '') {
    if (typeof params === 'object' && paramName in params && typeof params[paramName] !== 'undefined') {
        return params[paramName];
    }

    return defaultValue;
}
