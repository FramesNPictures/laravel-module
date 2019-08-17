<?php
/** @var $definition Fnp\Module\Frontend\FrontendModuleDefinition **/
?>

// {{ ucfirst($definition->getName()) }} Javascript

@if ($definition->getVueRootElement())
@include('fnp-module::frontend.vue')
@endif

// JS includes
@foreach($definition->getJs() as $path)
require('{{$path}}');
@endforeach

