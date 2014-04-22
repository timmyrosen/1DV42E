<?php
require('../../vendors/testing/SpeedTest.php');

use Framework\Testing\SpeedTest;

$speedTest = new SpeedTest();

/**
 * explode()
 * Searching for ":"
 */
echo '<h1>explode()</h1>';
$result = $speedTest->run(function() {
    $target = 'RootController:index';

    for ($i=0; $i<100000; $i++) {
        $return = explode(':', $target);
    }
});

echo 'Resultat: '.substr($result, 0, 10);

/**
 * preg_split()
 * Searching for ":", ".", "@"
 */
echo '<h1>preg_split()</h1>';
$result = $speedTest->run(function() {
    $target = 'RootController:index';

    for ($i=0; $i<100000; $i++) {
        $return = preg_split('/\:|\.|\@/', $target, 2);
    }
});

echo 'Resultat: '.substr($result, 0, 10);

/**
 * preg_match()
 * Searching for ":", ".", "@"
 */
echo '<h1>preg_match()</h1>';
$result = $speedTest->run(function() {
    $target = 'RootController:index';

    for ($i=0; $i<100000; $i++) {
        preg_match('/\:|\.|\@/', $target, $return);
    }
});

echo 'Resultat: '.substr($result, 0, 10);

/**
 * array_splice()
 */
echo '<h1>array_splice()</h1>';
$result = $speedTest->run(function() {
    for ($i=0; $i<100000; $i++) {
        $array = array('Hejsan', 'Hoppsan', 'Tjena', 'Tja');
        array_splice($array, 0, 1);
    }
});

echo 'Resultat: '.substr($result, 0, 10);

/**
 * array_shift()
 */
echo '<h1>array_shift()</h1>';
$result = $speedTest->run(function() {
    for ($i=0; $i<100000; $i++) {
        $array = array('Hejsan', 'Hoppsan', 'Tjena', 'Tja');
        array_shift($array);
    }
});

echo 'Resultat: '.substr($result, 0, 10);