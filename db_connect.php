<?php
require_once 'config.php';

function getConnection() {
    global $db_host, $db_user, $db_pass, $db_name;

    $connection = @new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($connection->connect_error) {
        error_log('DB connection failed: ' . $connection->connect_error);
        return null;
    }

    return $connection;
}
