@foreach($definition->getVueComponents() as $key => $path)
Vue.component('{{ $key }}', require('{{ $definition->relative($path) }}').default);
@endforeach