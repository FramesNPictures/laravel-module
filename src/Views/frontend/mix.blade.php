<?php
/** @var $definitions Fnp\Module\Frontend\FrontendModuleDefinition[] * */
?>
// Module: {{ $definition->getName() }}

let mix = require('laravel-mix');

@if($definition->getBuildInfo('js'))
mix.js('{{ $definition->getRelativeTargetModuleFilePath('js') }}', 'public/js').version();
@endif

@if($definition->getBuildInfo('css'))
mix.sass('{{ $definition->getRelativeTargetModuleFilePath('sass') }}', 'public/css').version();
@endif