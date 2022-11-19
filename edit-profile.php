<?php
    require 'header.php';

    $file_err = '';
    $id = $_GET['id'];

    if (isset($_GET['id'])) {
            
        $sql = "SELECT * FROM users WHERE id = ?";

        $stmt = $conn -> prepare($sql);
        $stmt -> bind_param('s', $id);
        $stmt -> execute();
        $result = $stmt -> get_result();
        
        if ($result -> num_rows > 0) {
            while ($row = $result->fetch_array()) {
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $profile = $row['profile'];
                $birthday = explode('-', $row['birthday']);
                $year = $birthday[0];
                $month = $birthday[1];
                $day = $birthday[2];
                $hometown = $row['hometown'];
                $education = $row['education'];
            }
        }
    }

    if (isset($_POST['submit'])) {

        $update_sql = 'UPDATE users SET firstname = ?, lastname = ?, education = ?,
        hometown = ? WHERE id = ?';

        if (!empty(trim($_POST['firstname']))) {
            $firstname = trim($_POST['firstname']);
        }
        if (!empty(trim($_POST['lastname']))) {
            $lastname = trim($_POST['lastname']);
        }
        if (!empty(trim($_POST['hometown']))) {
            $hometown = trim($_POST['hometown']);
        }
        if (!empty(trim($_POST['education']))) {
            $education = trim($_POST['education']);
        }
        if (isset($firstname) && isset($lastname) && isset($education) && isset($hometown)) {
            $stmt = $conn -> prepare($update_sql);
            $stmt -> bind_param('sssss', $firstname, $lastname, $education, $hometown, $id);
            $stmt -> execute();
            if ($stmt) {
                $stmt -> close();
            }
        }
    }
?>
<style>
    .form-container {
        display: flex;
        flex-direction: column;
        width: 50vw;
        margin: 5vh 25vw 0vh 25vw;
        padding-top: 3%;
        padding-bottom: 3%;
    }

    .form-profile {
        width: 50vw;
        margin: 2vh 25vw 10vh 25vw;
        padding: 1%;
    }

    .form-group {
        width: 80%;
        margin-left: 10%;
    }

    .birthday-selection {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    #year {
        width: 35%;
    }
    #month {
        width: 25%;
    }
    #day {
        width: 20%;
    }
    #btnSubmit {
        width: 100%;
        font-weight: 600;
    }

    @media only screen and (max-width: 600px) {
        .form-container {
            width: 100vw;
            margin: 5vh 0vw 1vh 0vw;
            padding: 0;
        }

        .form-profile {
            width: 100vw;
            margin: 5vh 0vw 1vh 0vw;
            padding: 0;
        }

        .form-container .birthday-selection {
            display: flex;
            flex-direction: column;
        }

        #year {
            width: 100%;
            margin-bottom: 20px;
        }
        #month {
            width: 100%;
            margin-bottom: 20px;
        }
        #day {
            width: 100%;
        }
    }
</style>
<form id="form-container" class="form-container" action="" method="POST">
    <div class="form-group">
        <b>First name:</b>
    </div>
    <div class="form-group">
        <input required type="text" class="form-control" value="<?php echo($firstname); ?>"  name="firstname">
    </div>
    <div class="form-group">
        <b>Last name:</b>
    </div>
    <div class="form-group">
        <input required type="text" class="form-control" value="<?php echo($lastname); ?>" name="lastname">
    </div>   
    <div class="form-group">
        <b style="margin-bottom: 20px;">Birthday:</b>
    </div>
    <div class="form-group birthday-selection">
        <select disabled class="form-control form-selec" name="year" id="year">
            <option disabled selected value="<?php echo($year); ?>"><?php echo($year); ?></option>
        </select>
        <select disabled class="form-control form-selec" name="month" id="month">
            <option disabled selected value="<?php echo($month); ?>"><?php echo($month); ?></option>
        </select>
        <select disabled class="form-control form-selec" name="day" id="day">
            <option disabled selected value="<?php echo($day); ?>"><?php echo($day); ?></option>
        </select>
    </div>
    <div class="form-group">
        <b>Hometown:</b>
    </div>
    <div class="form-group">
        <input required type="text" class="form-control" value="<?php echo($hometown); ?>" name="hometown">
    </div>
    <div class="form-group">
        <b>Education:</b>
    </div>
    <div class="form-group">
        <input required type="text" class="form-control" value="<?php echo($education); ?>" name="education">
    </div>
    <div style="margin-top: 25px;" class="form-group">
        <input id="btnSubmit" class="btn btn-primary" name="submit" type="submit" value="Save">
    </div>
</form>
<form id="form-profile" class="form-profile" enctype='multipart/form-data' action="profile-update.php?id=<?php echo($id); ?>" method="POST">
    <div class="form-group">
        <b>Update your profile picture here:</b>
    </div>
    <div class="form-group">
        <input type="file" name="file" style="width: 100%">
    </div>
    <div class="form-group">
        <input id="btnSubmit" class="btn btn-primary" name="upload" type="submit" value="Upload">
    </div>
    <div class="form-group">
        <p>You can upload: <b>.jpeg</b>; <b>.jpg</b>; <b>.png</b> files. Up to a 1000 mb.</p>
    </div>
</form>
<script>
    if ($(window).width() < 1000) {
    } else {
        $('#form-container').addClass('card');
    }
    if ($(window).width() < 1000) {
    } else {
        $('#form-profile').addClass('card');
    }
</script>