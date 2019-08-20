window.Vue = require('vue');

const app = new Vue(
    {
        el     : '{{ $definition->getVueRootElement() }}',
        data   : {!! json_encode($definition->getVueData()) !!}
    }
);

window.app = app;
