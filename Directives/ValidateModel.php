<?php /*dlv-code-engine***/

$schema = json_encode($state->map($config['name'])->read());
$validator = new \Opis\JsonSchema\Validator();

$data = json_decode(json_encode($state->memory()->get($config['name'])));//array to object
$result = $validator->validate($data, $schema);

if ( $result->isValid() ) {
    return;
}

if (isset($config['server-side-error']) && $config['server-side-error'] === true) {
    $state->memory()->set('error.status', 502);
    $state->memory()->set('error.message', 'Server Side Error');
    throw new \Exception('Server Side Error');
}

$formatter = new \Opis\JsonSchema\Errors\ErrorFormatter();
$message = $formatter->formatErrorMessage($result->error());

$state->memory()->set('error.status', 400);
$state->memory()->set('error.message', $message);
throw new \Exception($message);