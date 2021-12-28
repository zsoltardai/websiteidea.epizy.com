<?php
    
    require 'includes/config.inc.php';

    if (!empty($_POST['content'])) {
        $content = trim($_POST['content']);

        if (!empty($_POST['user_id'])) {
            $user_id = trim($_POST['user_id']);

            $sql = "INSERT INTO posts(user_id, content) VALUES(?, ?)";

            $stmt = $conn -> prepare($sql);
            $stmt -> bind_param('ss', $user_id, $content);

            $stmt -> execute();

            if ($stmt) {
                $sql_select = "SELECT user_id, date, posts.id, content, profile,
                first_name, last_name FROM posts INNER JOIN users ON
                posts.user_id = users.id WHERE content = ? AND user_id = ? ORDER BY date DESC LIMIT 1";

                $stmt = $conn -> prepare($sql_select);
                $stmt -> bind_param('ss', $content, $user_id);
                $stmt -> execute();
                $result = $stmt -> get_result();

                if (($result -> num_rows) > 0) {
                    while ($row = $result->fetch_array()) {
                        $sql_reactions = "SELECT user_id, reaction FROM reactions WHERE post_id = ?";
                        $stmt_reactions = $conn -> prepare($sql_reactions);
                        $stmt_reactions -> bind_param('s', $row['id']);
                        $stmt_reactions -> execute();
                        $result_reactions = $stmt_reactions -> get_result();
                        if ($result_reactions) {
                            $num_likes = 0;
                            $num_dislikes = 0;
                            $thumbs_up = '<i style="font-size: 1.5rem; color: #007bff;" class="far fa-thumbs-up fa-1x"></i>';
                            $thumbs_down = '<i style="font-size: 1.5rem; color: #007bff;" class="far fa-thumbs-down fa-1x"></i>';
                            while ($row_reactions = $result_reactions -> fetch_array()) {
                                if ($row_reactions['reaction'] == 1) {
                                    $num_likes += 1;
                                    if ($row_reactions['user_id'] == $user_id) {
                                        $thumbs_up = '<i style="font-size: 1.5rem; color: #007bff;" class="fas fa-thumbs-up"></i>';
                                    }
                                } else if ($row_reactions['reaction'] == 0) {
                                    $num_dislikes += 1;
                                    if ($row_reactions['user_id'] == $user_id) {
                                        $thumbs_down = '<i style="font-size: 1.5rem; color: #007bff;" class="fas fa-thumbs-down fa-1x"></i>';
                                    }
                                }
                            }
                        }
                        echo('
                            <div class="post card">
                                <div class="post-top">
                                    <a class="post-name" href="profile.php?id='.$row['user_id'].'">
                                        <img src="'.$row['profile'].'" alt="profile" />
                                        <div style="line-height: 40px;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                    </a>
                                    <a href="/post.php?id='.$row['id'].'">
                                        <div class="post-date" style="line-height: 40px;">
                                            '.date("F j, Y, g:i a", strtotime($row['date'])).'
                                        </div>
                                    </a>
                                </div>
                                <hr>
                                <div class="post-bottom">
                                    <div>
                                        '.$row['content'].'
                                    </div>
                                </div>
                                <hr>
                                <div class="post-reactions">
                                    <script>
                                        $(document).ready(function () {
                                            $("#btn_post_'.$row['id'].'_like").click(function(){
                                                $.post("reactions.php",
                                                {
                                                    post_id : "'.$row['id'].'",
                                                    reaction: 1,
                                                    user_id : '.$user_id.'
                                                },
                                                function(data, status){
                                                    var num_reactions_'.$row['id'].' =  JSON.parse(data);
                                                    $("#btn_post_'.$row['id'].'_like").html(thumbsUpFill);
                                                    $("#btn_post_'.$row['id'].'_dislike").html(thumbsDownEmpty);
                                                    $("#num_likes_for_'.$row['id'].'").text(num_reactions_'.$row['id'].'.likes);
                                                    $("#num_dislikes_for_'.$row['id'].'").text(num_reactions_'.$row['id'].'.dislikes);
                                                });
                                                });
                                                $("#btn_post_'.$row['id'].'_dislike").click(function () {
                                                $.post("reactions.php",
                                                {
                                                    post_id : "'.$row['id'].'",
                                                    reaction: 0,
                                                    user_id : '.$user_id.'
                                                },
                                                function(data, status){
                                                    var num_reactions_'.$row['id'].' =  JSON.parse(data);
                                                    $("#btn_post_'.$row['id'].'_like").html(thumbsUpEmpty);
                                                    $("#btn_post_'.$row['id'].'_dislike").html(thumbsDownFill);
                                                    $("#num_likes_for_'.$row['id'].'").text(num_reactions_'.$row['id'].'.likes);
                                                    $("#num_dislikes_for_'.$row['id'].'").text(num_reactions_'.$row['id'].'.dislikes);
                                                });
                                                });
                                        });
                                    </script>
                                    <div class="post-reactions">    
                                        <p style="color: #007bff;"><b id="num_likes_for_'.$row['id'].'">'.$num_likes.'</b></p>
                                        <button id="btn_post_'.$row['id'].'_like" style="height: 35px;" class="btn" type="submit" name="like">
                                            '.$thumbs_up.'
                                        </button>
                                        <p style="color: #007bff;"><b id="num_dislikes_for_'.$row['id'].'">'.$num_dislikes.'</b></p>
                                        <button id="btn_post_'.$row['id'].'_dislike" style="height: 35px;" class="btn" type="submit" name="dislike">
                                            '.$thumbs_down.'
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ');
                    }
                }
            }
        }
    }
?>