<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sys_cadastro';

$conn = new PDO("mysql:host=$host;dbname=".$dbname, $user, $pass);
