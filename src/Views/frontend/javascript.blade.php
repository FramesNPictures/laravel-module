// Javascript module for "{{ $definition->getHandle() }}"

function {{ $definition->getModuleMethodName('load','vueComponents') }}(vue) {
@if(count($definition->getVueComponents()))
@foreach($definition->getVueComponents() as $key => $path)
@if(strpos($path,'/') !== FALSE)
    vue.component('{{ $key }}', require('{{ $definition->relative($path) }}').default);
@else
    vue.component('{{ $key }}', require('{{ $path }}').default);
@endif
@endforeach
@else
    // No Vue Components
@endif
}

function {{ $definition->getModuleMethodName('load','vueData') }}() {
@if(count($definition->getVueData()))
    return {!! json_encode($definition->getVueData(), JSON_PRETTY_PRINT) !!};
@else
    // No Vue Data
@endif
}

function {{ $definition->getModuleMethodName('init','VueJS') }}(element) {
    window.Vue = require('vue');

    let v = new Vue({
        el: element,
        data: {{ $definition->getModuleMethodName('load','vueData') }},
    });

    return v;
}

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

function {{ $definition->getModuleMethodName('load','javaScript') }}() {
@if(count($definition->getJs()))
    @foreach($definition->getJs() as $path)
        require('{{ $definition->relative($path) }}');
    @endforeach
@else
    // No Javascript Included
@endif
}

{{ $definition->getModuleMethodName('load','javaScript') }}();



