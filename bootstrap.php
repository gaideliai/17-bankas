<?php

session_start();

$URL = 'http://localhost/uzdaviniai/bankas/';

$data = json_decode(file_get_contents(__DIR__ .'/accounts.json'), 1);
$loginData = json_decode(file_get_contents(__DIR__ .'/login.json'), 1);

// _d($data);