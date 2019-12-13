<?php /** @var \Fnp\Module\Definitions\FrontendModuleDefinition $definition */ ?>

// Javascript for "<?php echo($definition->getHandle()); ?>" Module

function <?php echo $definition->getModuleMethodName('load', 'javaScript'); ?>() {
<?php if (count($definition->getJs())): ?>
<?php foreach($definition->getJs() as $path): ?>
    require('<?php $definition->relative($path); ?>');
<?php endforeach; ?>
<?php else: ?>
    // No Javascript Included
<?php endif; ?>
}

function <?php echo $definition->getModuleMethodName('load','vueComponents'); ?>(vue) {
<?php if(count($definition->getVueComponents())): ?>
    <?php foreach($definition->getVueComponents() as $key => $path): ?>
    <?php if(strpos($path,'/') !== FALSE): ?>
    vue.component('{{ $key }}', require('{{ $definition->relative($path) }}').default);
    <?php else: ?>
    vue.component('{{ $key }}', require('{{ $path }}').default);
    <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    // No Vue Components
<?php endif; ?>
}

