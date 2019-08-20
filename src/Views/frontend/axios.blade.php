<?php
/** @var $definition Fnp\Module\Frontend\FrontendModuleDefinition **/
?>

/**
* We'll load the axios HTTP library which allows us to easily issue requests
* to our Laravel back-end. This library automatically handles sending the
* CSRF token as a header based on the value of the "XSRF" token cookie.
*/

window.axios = require('axios');

let token = document.head.querySelector('meta[name="id"]');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    window.axios.defaults.headers.common['Accept'] = 'application/json';
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token.content;
    window.axios.defaults.params = {};
    window.axios.defaults.params['token'] = token.content;

    console.info('Using API Token', token.content);
} else {
    console.error('API Token not found');
}

/**
* Next we will register the CSRF Token as a common header with Axios so that
* all outgoing HTTP requests automatically have it attached. This is just
* a simple convenience so we don't have to attach every token manually.
*/

token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}