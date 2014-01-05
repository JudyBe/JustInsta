<?php

require_once 'vendor/autoload.php';

use JustInsta\Instagram;
use JustInsta\CurlService;

$curl = new CurlService;
$instagram = new Instagram($curl);

$data = [
  ['login', 'password'],
  ['login', 'password'],
  ['login', 'password'],
  ['login', 'password'],
];

foreach ($data as $loginpair) {
  if ($instagram->login($loginpair[0], $loginpair[1]))
    echo "Logged In!\tUsername: {$loginpair[0]}, Password: {$loginpair[1]}", PHP_EOL;
  else
    echo $instagram->getErrorMessage(), PHP_EOL;
}