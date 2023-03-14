<?php /*dlv-code-engine***/

$state->memory()->set('local-call-response',null);

$apiStorage = \Illuminate\Support\Facades\App::make(\App\Core\ApiStorage::class);
$pathInterpreter = new \App\Core\PathInterpreter();
$apiMemoryFactory = new \App\Adapters\ApiMemoryFactory();
$engineDispatcher = \Illuminate\Support\Facades\App::make(\App\Core\EngineDispatcher::class);
$org = $state->memory()->get('hefesto-org');
$env = $state->memory()->get('hefesto-env');

$segments = explode('/',$config['path']);
if (isset($segments[1])) {
    $key = $segments[1];
} else {
    $state->message()->setStatus(404);
    return;
}
unset($segments[0]);
unset($segments[1]);
$config['path'] = '/'.implode('/',$segments);

$api = $apiStorage->find(
    $org,
    $env,
    $key
);

if ( !$api || !$api['active'] ) {
    $state->message()->setStatus(404);
    return;
}

$apiPath = config('app.API_NAMESPACE').$api['release'].'\\'.$api['release'];
$apiCode = new $apiPath();

$pathInfo = $pathInterpreter->execute($config['method'],$config['path'],$apiCode->actions());
if (is_null($pathInfo)) {
    $state->message()->setStatus(404);
    return;
}

$message = new \App\Core\Message(
    $config['method'],
    $config['path'],
    $config['headers'],
    $config['body'],
    $config['queryParams'],
    $pathInfo['PATH_PARAMS'],
    200
);

$remoteState = new \App\Core\State($message,[
    'organization' => $org,
    'environment' => $env,
    'apiMemory' => $apiMemoryFactory->make($org,$env,$api['key']),
    'keyApi' => $api['key'],
    'codePath' => config('app.CODE_PATH').$api['release'].'/',
    'storagePath' => config('app.STORAGE_PATH').$org.'/'.$env.'/'.$api['key'].'/',
    'localhost' => config('app.LOCALHOST'),
    'definitionVerb' => $pathInfo['DEFINITION_VERB'],
    'definitionPath' => $pathInfo['DEFINITION_PATH']
]);
$remoteState->memory()->set('hefestoLocalCall',true);

$engine = new \App\Core\Engine(
    $remoteState,
    $apiCode->getDirectives(
        $pathInfo['DEFINITION_VERB'],
        $pathInfo['DEFINITION_PATH']
    ),
    $engineDispatcher
);

$message = $engine->execute();
$engine->executeAfter();

$state->message()->setStatus($message->getStatus());
foreach ($message->getHeaders() as $key => $value) {
    $state->message()->setHeader($key,$value);
}
$state->message()->setBody($message->getBody());