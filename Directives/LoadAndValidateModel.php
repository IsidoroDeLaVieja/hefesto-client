<?php /*dlv-code-engine***/

LoadModel::run($state,[
    'source' => $config['source'],
    'target' => $config['target']
]);

ValidateModel::run($state,[
    'name' => $config['target'],
    'server-side-error' => isset($config['server-side-error']) && $config['server-side-error']
]);