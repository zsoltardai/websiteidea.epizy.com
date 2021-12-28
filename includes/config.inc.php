<?php

    $host = '';
    $username = '';
    $password = '';
    $database = '';

    $conn = new Mysqli($host, $username, $password, $database);

    if (!$conn) {
        echo('Something is wrong here...');
    }