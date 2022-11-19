<?php
    require 'header.php';

    if (isset($_SESSION['active']) == false || $_SESSION['active'] == false) {
        header('location: login.php' );
    }
?>
<style>
    .search-result {
        display: flex;
        flex-direction: row;
        height: 80px;
        margin-top: 15px;
        margin-bottom: 10px;
    }
    .search-result .left {
        width: 100px;
    }
    .search-result .left img {
        width: 80%;
        margin-left: 10%;
        margin-top: 10%;
        height: 80%;
        border-radius: 50%;
    }
    .search-result .right {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 70%;
    }
</style>
<main class="index-main">
    <div class="page-left">
        <?php require 'page-left.php'; ?>
    </div>
    <div class="mobile-search">
        <form action="index.php" method="GET" id="search-field">
            <input style="width: 100%;" id="search-input-mobile" name="text" class="form-control" placeholder="Are ya lookig for something?" type="text">
            <input hidden style="width: 25%; font-weight: 600;" class="btn btn-primary" name="submit" type="submit" value="Search">
        </form>
    </div>
    <div class="page-center">
        <div class="post-writing">
            <script>
                $(document).ready(function () {
                    $('#btnPost').click(function () {
                        var content = $("#postContent").val();
                        var userid = <?php echo($_SESSION['id']); ?>;
                        if (content) {
                            $.post('post-writing.php',
                            {
                                content : content,
                                userid : userid
                            },
                            function (data, status) {
                                if (status) {
                                    $('#posts').before(data);
                                    $("#postContent").val('');
                                }
                            });
                        }
                    });
                });
            </script>
            <textarea id="postContent" class="form-control" rows=4></textarea>
            <button class="btn btn-primary" style="width: 100%; margin-top: 10px;" id="btnPost"><b>POST</b></button>
        </div>
        <div id="posts">
            <?php 
                $load_posts = true;
                if (isset($_GET['submit'])) {

                    if (isset($_GET['text'])) {
                        $sql = "SELECT id, firstname, lastname, profile, hometown FROM 
                        users WHERE firstname LIKE ? OR lastname LIKE ?";
                        
                        $text = trim($_GET['text']);

                        $stmt = $conn -> prepare($sql);
                        $stmt -> bind_param('ss', $text, $text);
                        $stmt -> execute();
                        $result = $stmt -> get_result();

                        if (($result -> num_rows) > 0) {
                            echo('<div class="search-results">');
                                while ($row = $result->fetch_array()) {
                                    echo('
                                        <div class="card search-result">
                                            <div class="left">
                                                <a href="profile.php?id='.$row['id'].'">
                                                    <img src="'.$row['profile'].'"
                                                    alt="'.$row['firstname'].' '.$row['lastname']."'s profile".'"
                                                    title="'.$row['firstname'].' '.$row['lastname']."'s profile".'" />
                                                </a>
                                            </div>
                                            <div class="right">
                                                <a href="profile.php?id='.$row['id'].'">
                                                    <b>'.$row['firstname'].' '.$row['lastname'].'</b>
                                                </a>
                                                <p><b>Hometown: </b>'.$row['hometown'].'</p>
                                            </div>
                                        </div>
                                    ');
                                }
                            echo('</div>');
                        } else {
                            echo ("<h2>Looks like you're searching for someone special, cuz we couldn't find it..</h2>");
                        }
                    }
                } else {
                    if (isset($load_posts)) {
                        $sql_select = "SELECT userid, date, posts.id, content, profile,
                        firstname, lastname FROM posts INNER JOIN users ON
                        posts.userid = users.id ORDER BY date DESC LIMIT 25";

                        $stmt = $conn -> prepare($sql_select);
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
                }
            ?>
        </div>
    </div>
    <div class="page-right">

    </div>
</main>

<?php require 'footer.php'; ?>