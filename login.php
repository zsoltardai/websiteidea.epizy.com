<?php 

    require 'head.php';

    $email_err = $password_err = '';

    if (isset($_POST['submit'])) {

        if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
        }

        if (isset($_POST['password'])) {
            $password = trim($_POST['password']);
        }

        if (isset($email) && isset($password)) {
            $sql = "SELECT id, last_name, first_name, last_name, password, profile
            FROM users WHERE e_mail = ?";

            $stmt = $conn -> prepare($sql);
            $stmt -> bind_param('s', $email);
            $stmt -> execute();
            $result = $stmt -> get_result();

            if (($result -> num_rows) == 1) {
                while ($row = $result->fetch_array()) {
                    if (password_verify($password, $row['password'])) {
                        
                        
                        $_SESSION['active'] = true;
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['first_name'] = $row['first_name'];
                        $_SESSION['last_name'] = $row['last_name'];
                        $_SESSION['email'] = $email;
                        $_SESSION['profile'] = $row['profile'];

                        header('location: index.php');
                    } else {
                        $password_err = "The password you entered is invalid!";
                    }
                }
            } else {
                $email_err = "There's no account with this e-mail!";
            }
        }
    }
?>


<main>
    <style>
        .container {
            width: 40vw;
            height: 50vh;
            margin-top: 25vh;
            margin-left: 30vw;
        }
        .login-error {
            width: 80%;
            margin-left: 10%;
            text-align: left;
            color: #dc3545;
        }
        .bottom {
            padding: absolute;
            bottom: 0;
            display: flex;
            justify-content: center;
        }
        @media only screen and (max-width: 550px) {
            .container {
                width: 100vw;
                height: 60vh;
                margin-top: 20vh;
                margin-left: 0vw;
            }
            .login-error {
                font-size: 10pt;
            }
        }
    </style>
    <div class="container">
        <form action="login.php" method="POST">
            <div class="form-group" style="display: block; width: 100%; padding-left: 10%; padding-right: 10%;">
                <h2>Login</h2>
            </div>
            <div class="form-group">
                <input required style="width: 80%; margin-left: 10%;" type="text"
                    class="form-control" name="email" placeholder="E-mail">
                <div <?php if ($email_err != '') { echo('style="margin-top: 10px; margin-bottom: 5px"'); } else { echo('display: none;'); }; ?>>
                    <span class="login-error"><?php echo($email_err); ?></span>
                </div>
            </div>
            <div class="form-group">
                <input required style="width: 80%; margin-left: 10%;" type="password"
                    class="form-control" name="password" placeholder="Password">
                <div <?php if ($password_err != '') { echo('style="margin-top: 10px; margin-bottom: 5px"'); } else { echo('display: none;'); }; ?>>
                    <span class="login-error"><?php echo($password_err); ?></span>
                </div>
            </div>
            <div class="form-group">
                <input style="width: 80%; margin-left: 10%; font-weight: 600;" type="submit"
                    class="btn btn-primary" name="submit" value="Login">
            </div>
        </form>
        <div class="bottom">
            <p>Don't you have an account? <a href="/register.php">Register</a> here.</p>
        </div>
    </div>
    <script>
        $('input').on('focusout', function () {
            if ($(this).val().length === 0) {
                if ($(this).hasClass('is-valid')) {
                    $(this).removeClass('is-valid');
                }
                $(this).addClass('is-invalid');
                $(this).removeAttr('placeholder');
            } else {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                }
                $(this).addClass('is-valid');
            }
        });
    </script>
</main>

<?php require 'footer.php'; ?>