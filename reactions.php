<?php 
    require 'includes/config.inc.php';

    if (isset($_POST['user_id'])) {
        
        $user_id = trim($_POST['user_id']);
        
        if (isset($_POST['post_id'])) {

            $post_id = trim($_POST['post_id']);

            if (isset($_POST['post_id'])) {
                $reaction = trim($_POST['reaction']);

                $sql = "DELETE FROM reactions WHERE post_id = ? AND user_id = ?";
                $stmt = $conn -> prepare($sql);
                $stmt -> bind_param('ss', $post_id, $user_id);
                $stmt -> execute();

                if ($stmt) {
                    $num_likes = 0;
                    $num_dislikes = 0;
                    $sql = "INSERT INTO reactions(user_id, post_id, reaction) VALUES(?, ?, ?)";
                    $stmt = $conn -> prepare($sql);
                    $stmt -> bind_param('sss', $user_id, $post_id, $reaction);
                    $stmt -> execute();
                    if ($stmt) {
                        $sql = "SELECT reaction FROM reactions WHERE post_id = ?";
                        $stmt = $conn -> prepare($sql);
                        $stmt -> bind_param('s', $post_id);
                        $stmt -> execute();
                        $result = $stmt -> get_result();
                        if ($result) {
                            while ($row = $result -> fetch_array()) {
                                if ($row['reaction'] == 1) {
                                    $num_likes += 1;
                                } else if ($row['reaction'] == 0) {
                                    $num_dislikes += 1;
                                }
                            }
                        }
                        $json = array('likes' => $num_likes, 'dislikes' => $num_dislikes);
                        echo json_encode($json);
                    }
                }
            }
        }
    } 
?>