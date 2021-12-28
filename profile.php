<?php
    require 'header.php';
?>

<style>
    .page-center .top {
        width:  90%;
        height: 210px;
        margin : 0% 5% 0% 5%;
        margin-top: 5vh;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .page-center .top .left {
        width: 30%;
        align-items: center;
    }
    .page-center .top .left img {
        width: 120px;
        margin-left: 30px;
        margin-top: 30px;
        height: 120px;
        border-radius: 50%;
        border: 1px solid grey;
    }
    .page-center .top .right {
        width: 60%;
        height: 180px;
        margin: 30px 15px 10% 30px;
    }
    
    .page-center .bottom {
        width: 80%;
        height: 40vh;
        margin: 0px 10% 0px 10%;
    }
    @media only screen and (max-width: 1000px) {
        main {
            margin-bottom: 2vh;
            padding-bottom: 2vh;
        }
        .page-center .top {
            width: 90vw;
            height: auto;
            margin: 2vh 0vw 2vh 0vw;
            display: flex;
            flex-direction: column;
        }
        .page-center .top .left {
            width: 100%;
            margin-top: 2.5vh;
            margin-bottom: 2.5vh;
            display: flex;
            justify-content: center;
        }
        .page-center .top .left img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0;
        }
        .page-center .top .right {
            width: 90%;
            margin: 5%;
            height: auto;
        }
        .page-center .top .right h2 {
            text-align: center;
        }
        .page-center .post-writing {
            width: 90vw;
            margin: 2vh 0vw 2vh 0vw;
        }
        .page-center .bottom {
            width: 90vw;
            margin: 2vh 0vw 2vh 0vw;
        }
    }
</style>
<main>
    <?php
        if (isset($_GET['id'])) {
            
            $sql = "SELECT * FROM users WHERE id = ?";
            $id = $_GET['id'];

            $stmt = $conn -> prepare($sql);
            $stmt -> bind_param('s', $id);
            $stmt -> execute();
            $result = $stmt -> get_result();
            
            if ($result -> num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $profile = $row['profile'];
                    $birthday = $row['birthday'];
                    $hometown = $row['hometown'];
                    $education = $row['education'];
                }
            }
        }

    ?>
    <div class="page-left">
        <?php require 'page-left.php'; ?>
    </div>
    <div class="page-center">
        <div class="top card">
            <div class="left">
                <img src="<?php echo($profile); ?>"
                    alt="<?php echo($first_name.' '.$last_name."'s profile"); ?>"
                    title="<?php echo($first_name.' '.$last_name."'s profile"); ?>">
            </div>
            <hr>
            <div class="right">
                <h2><?php echo($first_name.' '.$last_name); ?></h2>
                <table>
                    <tr>
                        <td style="width: 40%;"><b>Birthday: </b></td>
                        <td style="width: 55%;"><?php echo($birthday); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 40%;"><b>Hometown: </b></td>
                        <td style="width: 55%;"><?php echo($hometown); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 40%;"><b>Education: </b></td>
                        <td style="width: 55%;"><?php echo($education); ?></td>
                    </tr>
                </table>
                <div id="btnConnections">
                    <?php
                        if ($_SESSION['id'] == $_GET['id']) {
                            echo('<a class="btn" style="width: 100%;  margin-top: 15px;" href="edit-profile.php?id='.$_SESSION['id'].'">Edit</a>');
                        }
                    ?>
                </div>
            </div>     
        </div>
        <div class="bottom">
            <?php
                if (isset($_GET['id'])) {
                    $sql_select = "SELECT user_id, date, posts.id, content, profile,
                    first_name, last_name FROM posts INNER JOIN users ON
                    posts.user_id = users.id WHERE user_id = ? ORDER BY date DESC";
                    
                    $id = trim($_GET['id']);

                    $stmt = $conn -> prepare($sql_select);
                    $stmt -> bind_param('s', $id);
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
                                        if ($row_reactions['user_id'] == $_SESSION['id']) {
                                            $thumbs_up = '<i style="font-size: 1.5rem; color: #007bff;" class="fas fa-thumbs-up"></i>';
                                        }
                                    } else if ($row_reactions['reaction'] == 0) {
                                        $num_dislikes += 1;
                                        if ($row_reactions['user_id'] == $_SESSION['id']) {
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
                                                        user_id : '.$_SESSION['id'].'
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
                                                        user_id : '.$_SESSION['id'].'
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
    </div>
    <div class="page-right">
    </div>
</main>