<?php

session_start();

$URL = 'http://localhost/uzdaviniai/bankas/';

$data = json_decode(file_get_contents(__DIR__ .'/accounts.json'), 1);
$loginData = json_decode(file_get_contents(__DIR__ .'/login.json'), 1);

function sort_by_surname ($key) {
    return function ($a, $b) use ($key) {       
        return strnatcmp($a[$key], $b[$key]);
    };
}

usort($data, sort_by_surname('surname'));

_d($data);