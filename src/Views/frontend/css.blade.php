<?php
/** @var $definition Fnp\Module\Frontend\FrontendModuleDefinition * */
?>

@foreach($definition->getCss() as $stylesheet)
{!! '@import "'.$definition->relative($stylesheet).'";' !!}
@endforeach

@foreach($definition->getSass() as $stylesheet)
{!! '@import "'.$definition->relative($stylesheet).'";' !!}
@endforeach