<?php /*dlv-code-engine***/

$pathCodeComponents = explode('/',$state->memory()->get('hefesto-pathcode'));
$releaseName = $pathCodeComponents[count($pathCodeComponents) - 2];

$jsStaticFile = $config['staticBasePath'].$config['type'].'?r='.$releaseName.'&f='.implode(',',$config['files']);
$state->memory()->set($config['type'].'StaticFile',$jsStaticFile);
