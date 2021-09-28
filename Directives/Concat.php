<?php /*dlv-code-engine***/

$concat = '';

$i = 1;
$searchElements = true;
while ($searchElements) {
    $searchElements = false;
    if ( isset($config['element'.$i]) ) {
        $concat .= $config['element'.$i];
        $i++;
        $searchElements = true;
    }
}

$state->memory()->set( $config['target'] , $concat );