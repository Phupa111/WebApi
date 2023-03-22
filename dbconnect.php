<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'foodland2';

    $connect = new mysqli($servername, $username, $password, $dbname);
    $connect->set_charset("utf8");
?>