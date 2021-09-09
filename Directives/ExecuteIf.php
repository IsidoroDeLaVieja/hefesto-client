<?php /*dlv-code-engine***/

if ( isset($config['if']) && $config['if'] ) {
    $directive = __NAMESPACE__ . '\\' . $config['execute'];
    unset($config['if']);
    unset($config['execute']);
    $directive::run($state,$config);
}