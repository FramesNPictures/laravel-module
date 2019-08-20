<?php
/** @var $definition Fnp\Module\Frontend\FrontendModuleDefinition * */
?>

@if($definition->getBuildInfo('axios'))
require('{{'./'.$definition->getModuleFileName('axios.js')}}');
@endif

@if($definition->getBuildInfo('bootstrap'))
require('{{'./'.$definition->getModuleFileName('bootstrap.js')}}');
@endif

@if($definition->getBuildInfo('vue'))
window.Vue = require('vue');
@endif

@if($definition->getBuildInfo('components'))
require('{{'./'.$definition->getModuleFileName('components.js')}}');
@endif

@if($definition->getBuildInfo('vue'))
require('{{'./'.$definition->getModuleFileName('vue.js')}}');
@endif

@foreach($definition->getJs() as $path)
require('{{$definition->relative($path)}}');
@endforeach