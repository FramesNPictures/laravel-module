// Javascript module for "{{ $definition->getHandle() }}"

function {{ $definition->getModuleMethodName('load','javaScript') }}() {
@if(count($definition->getJs()))
@foreach($definition->getJs() as $path)
    require('{{ $definition->relative($path) }}');
@endforeach
@else
    // No Javascript Included
@endif
}

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
    return {!! json_encode($definition->getVueData, JSON_PRETTY_PRINT) !!};
@else
    // No Vue Data
@endif
}



