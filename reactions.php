<?php 
    require 'includes/config.inc.php';

    if (isset($_POST['userid'])) {
        
        $userid = trim($_POST['userid']);
        
        if (isset($_POST['postid'])) {

            $postid = trim($_POST['postid']);

            if (isset($_POST['postid'])) {
                $reaction = trim($_POST['reaction']);

                $sql = "DELETE FROM reactions WHERE postid = ? AND userid = ?";
                $stmt = $conn -> prepare($sql);
                $stmt -> bind_param('ss', $postid, $userid);
                $stmt -> execute();

                if ($stmt) {
                    $num_likes = 0;
                    $num_dislikes = 0;
                    $sql = "INSERT INTO reactions(userid, postid, reaction) VALUES(?, ?, ?)";
                    $stmt = $conn -> prepare($sql);
                    $stmt -> bind_param('sss', $userid, $postid, $reaction);
                    $stmt -> execute();
                    if ($stmt) {
                        $sql = "SELECT reaction FROM reactions WHERE postid = ?";
                        $stmt = $conn -> prepare($sql);
                        $stmt -> bind_param('s', $postid);
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