<?php require 'head.php';

    if (!isset($_SESSION['active']) || $_SESSION['active'] == false) {
        header('location: login.php');
    }

?>
<style>
    a:hover {
        text-decoration: none;
    }
    header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin: 15px 0vw 15px 0vw;
        height: 40px;
        line-height: 30px;
    }

    #left {
        width: 25%;
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }

    #center {
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }

    .mobile-search {
        display: none;
    }

    #right {
        width: 25%;
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }
    .btn-logout {
        width: auto;
        height: 50px;
        margin-top: 5px;
    }
    #left a img{
        height: 100%;
        width: auto;
    }
    #search-field {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }
    .page-left {
        height: calc(100vh - 70px);
        width: 25vw;
    }
    .page-center {
        width: 50vw;
        margin-top: 2.5vh;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .page-right {
        width: 25vw;
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }
    main {
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }
    .post{
        width: 100%;
        margin: 20px 0px 20px 0px;
        height: auto;
    }
    .post .post-top {
        width: 80%;
        margin: 20px 10% 2.5px 10%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .post .post-top .post-name {
        width: 40%;
        margin: 0%;
        display: flex;
        flex-direction: row;
        vertical-align: middle;
        font-weight: 600;
    }
    .post .post-top .post-name img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 25px;
    }
    .post .post-bottom {
        width: 80%;
        margin: 2px 10% 2px 10%;
    }
    .post .post-reactions {
        width: 80%;
    }
    .post .post-reactions form {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }
    .post .post-reactions form input {
        width: 50px;
        margin-left: 15px;
    }
    .post-writing {
        width: 100%;
        margin: 0vh 0% 0vh 0%;
        height: auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .post-writing textarea {
        width: 100%;
        margin-bottom: 1.5%;
        margin-top: 1.5%;
    }
    .post-writing input {
        width: 100%;
        margin-bottom: 1.5%;
        margin-top: 1.5%;
    }
    .post-reactions {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        line-height: 35px;
        font-size: 1.5rem;
    }
    .post-page {
        margin-left: 25vw;
        width: 50vw;
    }
    @media only screen and (max-width: 1000px) {
        header {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 15px 0vw 15px 0vw;
            height: 40px;
        }
        #center {
            display: none;
        }
        .mobile-search {
            display: block;
            width: 90vw;
            margin-left: 5vw;
        }
        #left a img{
            height: 35px;
            width: auto;
        }
        #right {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
        }
        .btn-logout {
            height: 35px;
            width: auto;
            text-align: center;
            margin-right: 15px;
        }
        #search-field {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        main {
            display: flex;
            flex-direction: column;
        }
        .page-left {
            width: 100vw;
            height: auto;
            display: flex;
            flex-direction: column;
        }
        .page-center {
            width: 90vw;
            margin-left: 5vw;
            margin-top: 5vh;
            display: flex;
            flex-direction: column;
        }
        .page-right {
            width: 100vw;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 2vh;
            height: 5vh;
            content: ' ';
        }
        .post {
            width: 90vw;
            margin: 5vw 0vh 5vw 0vh;
        }
        .post .post-top{
            width: 90%;
            display: flex;
            flex-direction: column;
        }
        .post .post-top .post-name{
            width: 100%;
        }
        .post .post-top .post-date{
            width: 70%;
            margin-bottom: -10px;
        }
        .post-page {
            margin-left: 5vw;
            width: 90vw;
        }
    }
</style>
<header>
    <div id="left">
        <a href="index.php">
            <img src="img/logo.png" alt="this is the logo of the page">
        </a>
    </div>
    <div id="center">
        <form action="index.php" method="GET" id="search-field">
            <input style="width: 70%;" id="search-input" name="text" class="form-control" placeholder="Are ya lookig for something?" type="text">
            <input style="width: 15%; height: 40px; font-weight: 600;" class="btn btn-primary" name="submit" type="submit" value="Search">
        </form>
    </div>
    <div id="right" style="font-size: 1.25rem;">
        <a href="logout.php" style="line-height: 40px;">
            <i class="btn-logout fas fa-sign-out-alt fa-1x"></i>
        </a>
    </div>
</header>
<script>
    $('#search-input').on('focusin', function () {
        $(this).attr('placeholder', '');
    });

    const searchTexts = new Array('Are ya lookig for something?', 'Looking for something?',
    'Sup?', 'Type something here...', 'Huh?');

    $('#search-input, #search-input-mobile').on('focusout', function () {
        $(this).attr('placeholder', searchTexts[Math.floor(Math.random() * searchTexts.length)]);
    });
</script>