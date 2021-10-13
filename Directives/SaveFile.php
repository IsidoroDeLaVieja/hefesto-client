<?php /*dlv-code-engine***/

file_put_contents(
        $state->memory()->get('hefesto-pathstorage').$config['name']
    ,   $config['content']
);