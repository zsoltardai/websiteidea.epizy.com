<?php 
    require 'includes/config.inc.php';

    session_start();

    $id = $_GET['id'];
    $sql = 'UPDATE users SET profile = ? WHERE id = ?';

    if (isset($_POST['upload'])) {

        $file = $_FILES['file'];
        $file_name = $_FILES['file']['name'];
        $file_tmp_name = $_FILES['file']['tmp_name'];
        list($width, $height, $type, $attr) = getimagesize( $_FILES['file']['tmp_name'] );
        $file_size = $_FILES['file']['size'];
        $file_error = $_FILES['file']['error'];
        $file_type = $_FILES['file']['type'];

        $file_extension = explode('.', $file_name);
        $file_actual_extension = strtolower(end($file_extension));

        $allowed = array('png', 'jpg', 'jpeg');
        $size = getimagesize( $file_tmp_name);
        $src = imagecreatefromstring(file_get_contents($file_tmp_name));
        $dst = imagecreatetruecolor( 100, 100 );
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1] );

        imagejpeg($dst, $target_filename); 

        if (in_array($file_actual_extension, $allowed)) {
            if ($file_error === 0) {
                if ($file_size < 1000000) {
                    $file_name_new = uniqid('', true).".".$file_actual_extension;
                    $file_destination = "profiles/".$file_name_new;
                    move_uploaded_file($file_tmp_name, $file_destination);
                    if (strval($_SESSION['profile']) != 'profiles/default.png') {
                        if (unlink($_SESSION['profile'])) {
                            $_SESSION['profile'] = $file_destination;
                        }
                    }
                    $stmt = $conn -> prepare($sql);
                    $stmt -> bind_param('ss', $file_destination, $id);
                    $stmt -> execute();
                    header('location: profile.php?id='.$id);
                } else {
                    header('location: edit_profile.php?id='.$id);
                }
            }
        } else {
            header('location: edit_profile.php?id='.$id);
        }
    }