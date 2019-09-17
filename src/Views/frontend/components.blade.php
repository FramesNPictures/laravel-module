@foreach($definition->getVueComponents() as $key => $path)
@if(strpos($path,'/'))
Vue.component('{{ $key }}', require('{{ $definition->relative($path) }}').default);
@else
Vue.component('{{ $key }}', require('{{ $path }}').default);
@endif
@endforeach