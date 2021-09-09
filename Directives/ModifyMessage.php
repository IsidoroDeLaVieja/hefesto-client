<?php /*dlv-code-engine***/

if (isset($config['verb']) )
{
    $state->message()->setVerb($config['verb']);
}

$queryParamsInPath = false;
if (isset($config['path']) )
{
    $oldQueryParams = $state->message()->getQueryParams();
    $state->message()->setPath($config['path']);
    $queryParamsInPath = $oldQueryParams !== $state->message()->getQueryParams();
}

if (isset($config['headers']))
{
    $state->message()->deleteHeaders();
    foreach ($config['headers'] as $key => $value) {
        $state->message()->setHeader($key,$state->alias($value));
    }
}

if (isset($config['queryParams']) && !$queryParamsInPath)
{
    $state->message()->deleteQueryParams();
    foreach ($config['queryParams'] as $key => $value) {
        $state->message()->setQueryParam($key,$state->alias($value));
    }
}

if (isset($config['body']) && is_array($config['body']))
{
    $state->message()->setBodyAsArray($config['body']);
} else if (isset($config['body']) && is_string($config['body'])) {
    $state->message()->setBody($config['body']);
}

if (isset($config['status']) )
{
    $state->message()->setStatus($config['status']);
}