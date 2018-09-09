<?php

include_once 'sql.php';

$server_name = 'localhost';
$user = 'root';
$password = '1234';
$db_name = 'user.kg';

$users_db = new Users($server_name, $user, $password, $db_name);