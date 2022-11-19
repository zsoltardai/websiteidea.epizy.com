<?php 
    require 'header.php';
?>
<div class="post-page">
    <?php
        if (isset($_GET['id'])) {
            $sql_select = "SELECT userid, date, posts.id, content, profile,
            firstname, lastname FROM posts INNER JOIN users ON
            posts.userid = users.id WHERE posts.id = ? ORDER BY date DESC";
            
            $id = trim($_GET['id']);

            $stmt = $conn -> prepare($sql_select);
            $stmt -> bind_param('s', $id);
            $stmt -> execute();
            $result = $stmt -> get_result();

            if (($result -> num_rows) > 0) {
                while ($row = $result->fetch_array()) {
                    $sql_reactions = "SELECT userid, reaction FROM reactions WHERE postid = ?";
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
                                if ($row_reactions['userid'] == $_SESSION['id']) {
                                    $thumbs_up = '<i style="font-size: 1.5rem; color: #007bff;" class="fas fa-thumbs-up"></i>';
                                }
                            } else if ($row_reactions['reaction'] == 0) {
                                $num_dislikes += 1;
                                if ($row_reactions['userid'] == $_SESSION['id']) {
                                    $thumbs_down = '<i style="font-size: 1.5rem; color: #007bff;" class="fas fa-thumbs-down fa-1x"></i>';
                                }
                            }
                        }
                    }
                    echo('
                        <div class="post card">
                            <div class="post-top">
                                <a class="post-name" href="profile.php?id='.$row['userid'].'">
                                    <img src="'.$row['profile'].'" alt="profile" />
                                    <div style="line-height: 40px;">'.$row['firstname'].' '.$row['lastname'].'</div>
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
                                                postid : "'.$row['id'].'",
                                                reaction: 1,
                                                userid : '.$_SESSION['id'].'
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
                                                postid : "'.$row['id'].'",
                                                reaction: 0,
                                                userid : '.$_SESSION['id'].'
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
    ?>
</div>