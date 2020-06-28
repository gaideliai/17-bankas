<?php

session_start();

// $URL = 'http://localhost/uzdaviniai/bankas/';
$URL = '/';

$data = json_decode(file_get_contents(__DIR__ .'/accounts.json'), 1);
$loginData = json_decode(file_get_contents(__DIR__ .'/login.json'), 1);

function sort_by_surname ($key) {
    return function ($a, $b) use ($key) {       
        return strnatcmp($a[$key], $b[$key]);
    };
}

usort($data, sort_by_surname('surname'));

// _d($data);

function formatIban($number) {
    $number = (string)$number;
    $string = substr($number, 0, 4);
    for ($i=4; $i < strlen($number); $i=$i+4) { 
        $string .= ' ';
        $string .= substr($number, $i, 4);
    }
    return $string;
}