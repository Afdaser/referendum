<div class="page-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
<?php

$keys = [
    '@bower',
    '@npm',
//    '@bar',
    '@common',
    '@backend',
    '@frontend'
    ];
    echo '<h1>Yii::getAlias(*)</h1>';
foreach ($keys as $key) {
    echo '<h2>{$key}'.Yii::getAlias($key).'</h2>';
}


$keys = ['app', 'back', 'front', 'core', 'base'];
$words = ['key', 'path', 'Home'];

foreach ($keys as $key) {
    echo "<h2>{$key}</h2>";

    echo '<ul>';
    foreach ($words as $word) {
        echo "<li>k:{$key} - w:{$word} ::" . Yii::t($key, $word) . '</li>';
    }
    echo '</ul>';
}
