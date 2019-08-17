<?php
/** @var $definitions Fnp\Module\Frontend\FrontendModuleDefinition[] * */
?>

// Automatically built to include all the modules
// in the Laravel Mix build process

let mix = require('laravel-mix');

@foreach($definitions as $definition)

@if($definition->getBuildInfo('hasJs'))
mix.js('{{ $definition->getRelativeTargetModuleFilePath('js') }}',
        '{{ config('module.path.relativePublic').'/js' }}')
   .version();
@endif

@if($definition->getBuildInfo('hasCss'))
mix.sass('{{ $definition->getRelativeTargetModuleFilePath('sass') }}',
        '{{ config('module.path.relativePublic').'/css' }}')
   .version();
@endif

@endforeach