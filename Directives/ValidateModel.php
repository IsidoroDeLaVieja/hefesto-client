<?php /*dlv-code-engine***/

$schema = json_encode($state->map($config['name'])->read());
$validator = new \Opis\JsonSchema\Validator();

$data = json_decode(json_encode($state->memory()->get($config['name'])));
$result = $validator->validate($data, $schema);

if ( ! $result->isValid() ) {
    if (isset($config['server-side-error']) && $config['server-side-error'] === true) {
        $state->memory()->set('error.status', '502');
        $state->memory()->set('error.message', 'Server Side Error');
        throw new \Exception('Server Side Error');
    }
    
    $error = $result->getFirstError();
    
    $wrongData = $error->dataPointer();
    $wrongData = $wrongData ? $wrongData[0] : '';
    $message = $wrongData.' '.$error->keyword().' ';
    
    $keywordArgs = $error->keywordArgs();
    foreach($keywordArgs as $key => $value) {
        $value = is_array($value) ? implode(' ',$value) : $value;
        $message .= $key.' '.$value.' ';
    }
    
    $state->memory()->set('error.status', '400');
    $state->memory()->set('error.message', trim($message));
    throw new \Exception(trim($message));
}
