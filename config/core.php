<?php
    //Mostrar reporte de error
    error_reporting(E_ALL);
    
    //Setear TIME_ZONE
    date_default_timezone_set('America/Buenos_Aires');
    
    //Variables usadas por JWT
    $key = "fastshop";
    $iss = "http://example.org";
    $aud = "http://example.com";
    $iat = 1356999524;
    $nbf = 1357000000;
?>