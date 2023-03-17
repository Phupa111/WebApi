<?php
    $servername = 'localhost';
    $username = 'proweb';
    $password = 'abc123';
    $dbname = 'proweb';

    $connect = new mysqli($servername, $username, $password, $dbname);
    $connect->set_charset("utf8");
?>