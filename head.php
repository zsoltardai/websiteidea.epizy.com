<?php

    include 'includes/config.inc.php'; 
    session_start();
    require 'loading-screen.html';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
        <script src="https://kit.fontawesome.com/1c819c2c7e.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <script>
            $(document).ready( function () {
                $('#loading-screen').remove();
            });
        </script>
        <script>
            const thumbsUpFill = `<i style="font-size: 1.5rem; color: #007bff;" class="fas fa-thumbs-up"></i>`;
            const thumbsUpEmpty = `<i style="font-size: 1.5rem; color: #007bff;" class="far fa-thumbs-up fa-1x"></i>`;
            const thumbsDownFill = `<i style="font-size: 1.5rem; color: #007bff;" class="fas fa-thumbs-down fa-1x"></i>`;
            const thumbsDownEmpty = `<i style="font-size: 1.5rem; color: #007bff;" class="far fa-thumbs-down fa-1x"></i>`;
        </script>
    </head>
    <body>