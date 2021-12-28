<?php
    require 'head.php';

    if (isset($_SESSION['active']) && $_SESSION['active'] == true) {
        header('location: index.php');
    }

    $email_regex = '/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i';
    $password_regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/';
    $sql_for_email = "SELECT * FROM users WHERE e_mail = ?";
    $sql_for_insert = "INSERT INTO users (first_name, last_name, birthday, e_mail, profile, password) VALUES (?, ?, ?, ?, ?, ?)";
    $email_err = '';
    $profile = 'profiles/default.png';

    if (isset($_POST['submit'])) {
        if (!empty(trim($_POST['first_name']))) {
            $first_name = trim($_POST['first_name']);
        }
        if (!empty(trim($_POST['last_name']))) {
            $last_name = trim($_POST['last_name']);
        }
        if (!empty(trim($_POST['year']))) {
            if (!empty(trim($_POST['month']))) {
                if (!empty(trim($_POST['day']))) {
                    $birthday = trim($_POST['year']).'.'.trim($_POST['month']).'.'.trim($_POST['day']).'.';
                }
            }
        }
        if (!empty(trim($_POST['email'])) && preg_match($email_regex, trim($_POST['email']))) {
            $stmt = $conn -> prepare($sql_for_email);
            $stmt -> bind_param("s", trim($_POST['email']));
            $stmt -> execute();
            $result = $stmt -> get_result();
            if (($result -> num_rows) > 0) {
                $email_err = "There's already an account for this email!";
            } else {
                $stmt -> close();
                $email = trim($_POST['email']);
            }
        }
        if (!empty(trim($_POST['password'])) && preg_match($password_regex, trim($_POST['password']))) {
            $password = trim($_POST['password']);
            if (!empty(trim($_POST['repassword'])) && trim($_POST['repassword']) == $password) {
                $hashed_password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
            }
        }
        if (isset($first_name) && isset($last_name) && isset($email) && isset($birthday) && isset($hashed_password)) {
            $stmt = $conn -> prepare($sql_for_insert);
            $stmt -> bind_param('ssssss', $first_name, $last_name, $birthday, $email, $profile, $hashed_password);
            $stmt -> execute();
            if ($stmt) {
                $stmt -> close();
                header('location: login.php');
            }
        }
    }

?>

<main>
    <style>
        .container {
            width: 40vw;
            margin-top: 15vh;
            margin-left: 30vw;
        }
        .birthday-selection {
            display: flex;
            width: 100%;
            padding-left: 10%;
            padding-right: 10%;
            flex-direction: row;
            justify-content: space-between;
        }
        .name-fields {
            display: flex;
            width: 100%;
            padding-left: 10%;
            padding-right: 10%;
            flex-direction: row;
            justify-content: space-between;
        }
        #year {
            width: 25%;
        }
        #month {
            width: 40%;
        }
        #day {
            width: 25%;
        }
        .name {
            width: 47.5%;
        }
        .mobileview {
            width: 100%;
            padding-left: 10%;
            padding-right: 10%;
            display: none;
        }
        .passwordError {
            width: 80%;
            margin-top: 10px;
            margin-left: 10%;
        }
        .bottom {
            padding: absolute;
            bottom: 0;
            display: flex;
            justify-content: center;
        }
        @media only screen and (max-width: 1100px) {
            .container {
                width: 70vw;
                margin-top: 10vh;
                margin-left: 15vw;
            }
            .birthday-selection {
                display: flex;
                width: 100%;
                padding-left: 10%;
                padding-right: 10%;
                flex-direction: column;
            }
            .name-fields {
                display: flex;
                width: 100%;
                padding-left: 10%;
                padding-right: 10%;
                flex-direction: column;
            }
            #year,
            #month {
                width: 100%;
                margin-bottom: 10px;
            }
            #day {
                width: 100%;
            }
            .name {
                width: 100%;
            }
            #first_name {
                margin-bottom: 10px;
            }
            .mobileview {
                display: block;
            }
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100vw;
                margin-top: 5vh;
                margin-left: 0vw;
            }
            .birthday-selection {
                display: flex;
                width: 100%;
                padding-left: 10%;
                padding-right: 10%;
                flex-direction: column;
            }
            .name-fields {
                display: flex;
                width: 100%;
                padding-left: 10%;
                padding-right: 10%;
                flex-direction: column;
            }
            #year,
            #month {
                width: 100%;
                margin-bottom: 10px;
            }
            #day {
                width: 100%;
            }
            .name {
                width: 100%;
            }
            #first_name {
                margin-bottom: 10px;
            }
            .mobileview {
                display: block;
            }
        }
    </style>
    <div class="container">
        <form width="100%" action="register.php" method="POST">
            <div class="form-group" style="display: block; width: 100%; padding-left: 10%; padding-right: 10%;">
                <h2>Registration</h2>
            </div>
            <div class="form-group mobileview">
                <b>Name:</b>
            </div>
            <div class="form-group name-fields">
                <input required id="first_name" name="first_name" type="text"
                    class="form-control name" placeholder="First name">
                <input required type="text" name="last_name"
                class="form-control name" placeholder="Last name">
            </div>
            <div class="form-group mobileview">
                <b>Birthday:</b>
            </div>
            <div class="form-group birthday-selection">
                <select required class="form-control form-selec" name="year" id="year">
                    <option disabled selected value="Year">Year</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                    <option value="2017">2017</option>
                    <option value="2016">2016</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    <option value="2013">2013</option>
                    <option value="2012">2012</option>
                    <option value="2011">2011</option>
                    <option value="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                    <option value="2004">2004</option>
                    <option value="2003">2003</option>
                    <option value="2002">2002</option>
                    <option value="2001">2001</option>
                    <option value="2000">2000</option>
                    <option value="1999">1999</option>
                    <option value="1998">1998</option>
                    <option value="1997">1997</option>
                    <option value="1996">1996</option>
                    <option value="1995">1995</option>
                    <option value="1994">1994</option>
                    <option value="1993">1993</option>
                    <option value="1992">1992</option>
                    <option value="1991">1991</option>
                    <option value="1990">1990</option>
                    <option value="1989">1989</option>
                    <option value="1988">1988</option>
                    <option value="1987">1987</option>
                    <option value="1986">1986</option>
                    <option value="1985">1985</option>
                    <option value="1984">1984</option>
                    <option value="1983">1983</option>
                    <option value="1982">1982</option>
                    <option value="1981">1981</option>
                    <option value="1980">1980</option>
                    <option value="1979">1979</option>
                    <option value="1978">1978</option>
                    <option value="1977">1977</option>
                    <option value="1976">1976</option>
                    <option value="1975">1975</option>
                    <option value="1974">1974</option>
                    <option value="1973">1973</option>
                    <option value="1972">1972</option>
                    <option value="1971">1971</option>
                    <option value="1970">1970</option>
                    <option value="1969">1969</option>
                    <option value="1968">1968</option>
                    <option value="1967">1967</option>
                    <option value="1966">1966</option>
                    <option value="1965">1965</option>
                    <option value="1964">1964</option>
                    <option value="1963">1963</option>
                    <option value="1962">1962</option>
                    <option value="1961">1961</option>
                    <option value="1960">1960</option>
                </select>
                <select required class="form-control form-selec" name="month" id="month">
                    <option disabled selected value>Month</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select required class="form-control form-selec" name="day" id="day">
                    <option disabled selected>Day</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                </select>
            </div>
            <div class="form-group mobileview">
                <b>E-mail:</b>
            </div>
            <div class="form-group">
                <input required style="width: 80%; margin-left: 10%;" id="email" type="email"
                    name="email" class="form-control" placeholder="E-mail">
                <span id="emailError" class="invalid-feedback"><?php echo($email_err); ?></span>
            </div>
            <div class="form-group mobileview">
                <b>Password:</b>
            </div>
            <div class="form-group">
                <input required style="width: 80%; margin-left: 10%;"  type="password"
                    class="form-control password" name="password" id="password"
                    placeholder="Password">
            </div>
            <div class="form-group mobileview">
                <b>Password:</b>
            </div>
            <div class="form-group">
                <input required style="width: 80%; margin-left: 10%;" type="password"
                    class="form-control password" name="repassword" id="repassword"
                    placeholder="Password">
            </div>
            <div class="form-group">
                <input style="width: 80%; margin-left: 10%; font-weight: 600;" type="submit"
                    class="btn btn-primary" name="submit" id="submit" value="Register">
            </div>
        </form>
        <div class="bottom">
            <p>Do you have an account? <a href="/login.php">Login</a> here.</p>
        </div>
    </div>
    <script>
        const emailRegex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;

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

        $('#email').on('focusout', function () {
            if (!emailRegex.test($(this).val())) {
                if ($(this).hasClass('is-valid')) {
                    $(this).removeClass('is-valid');
                }
                $(this).addClass('is-invalid');
            }
        });

        $('.password').on('focusout', function () {
            if (!passwordRegex.test($(this).val())) {
                if ($(this).hasClass('is-valid')) {
                    $(this).removeClass('is-valid');
                }
                $(this).addClass('is-invalid');
            }
        });

        const notAllowed = ['Year', 'Month', 'Day'];

        $('.form-selec').on('focusout', function () {
            if ($.inArray($(this), notAllowed) != -1) {
                if ($(this).hasClass('is-valid')) {
                    $(this).removeClass('is-valid');
                }
                $(this).addClass('is-invalid');
            }
        });

        var passwordError = '<div class="invalid-feedback passwordError" id="passwordError"><b>Your passwords do not match!</div><b>';
        var fillFieldError = '<div class="invalid-feedback passwordError fillFieldError"><b>You must to fill all the fields!</div><b>';

        $('#repassword').on('focusout', function () {
            $('#passwordError').remove();
            if ($('#password').val() != $(this).val()) {
                $(this).after(passwordError);
            } else {
                $('#passwordError').remove();
            }
        });

        $('#submit').on('click', function (event) {
            $('.fillFieldError').remove();
            $('input').each(function () {
                if ($(this).hasClass('is-invalid')) {
                    $(this).after(fillFieldError);
                    event.preventDefault();
                }
            })
        });
    </script>
</main>

<?php require 'footer.php'; ?>