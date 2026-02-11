<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function flag($code){
    if(!$code || strlen($code) !== 2) return '';
    $code = strtoupper($code);

    return mb_chr(127397 + ord($code[0])) .
           mb_chr(127397 + ord($code[1]));
}
