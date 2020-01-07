
function {{ $definition->getModuleMethodName('init','Axios') }}(csrfTokenMeta, authTokenMeta) {
    window.axios = require('axios');
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    window.axios.defaults.headers.common['Accept'] = 'application/json';

    if (csrfTokenMeta) {
        token = document.head.querySelector('meta[name="' + csrfTokenMeta + '"]');

        if (token)
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        else
            console.error('CSRF token not found: ' + csrfTokenMeta);
    }

    if (authTokenMeta) {
        token = document.head.querySelector('meta[name="' + authTokenMeta + '"]');

        if (token)
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token.content;
        else
            console.error('Auth token not found: ' + authTokenMeta);
    }
}