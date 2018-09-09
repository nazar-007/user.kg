<?php

session_start();
if (!$_SESSION['id'] || $_SESSION['role'] != 'admin') {
    header("Location: /");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update user</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/list.css">
</head>
<body>
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="list.php" class="navbar-brand">User.kg</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a><span class="glyphicon glyphicon-user"></span>
                        <?php echo $_SESSION['role']; ?>
                    </a></li>
                <li><a>
                        <form action="processes.php" method="post">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="btn-danger">Logout</button>
                        </form>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <?php
    include 'config.php';
    $id = $_GET['id'];
    $one_user = $users_db->getOneUserById($id);

    if (count($one_user) == 0):?>
    <h3 class='centered'>Sorry, but user with id <?php echo $id ?> does not exist! :(</h3;
        <?php else :
        foreach ($one_user as $user):?>
    <h3 class="centered">Update user</h3>

    <div class="row main">
        <div class="main-login main-center">
            <form method="post" action="javascript:void(0)" onsubmit="updateUser(this)">
                    <h4><b>Note:</b> If you don't change login or set new password, they will remain the same.</h4>
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" name="id" value="<?php echo $id?>">

                <div class="form-group">
                    <label for="login_input" class="cols-sm-2 control-label">Login</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="login" id="login_input" value="<?php echo $user['login']?>"/>
                        </div>
                        <div id="login_error" class="error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_input" class="cols-sm-2 control-label">Password</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" id="password_input"/>
                        </div>
                        <div id="password_error" class="error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="check_password_input" class="cols-sm-2 control-label">Check password</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="check_password" id="check_password_input"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nickname_input" class="cols-sm-2 control-label">Nickname</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="nickname" id="nickname_input" value="<?php echo $user['nickname']?>"/>
                        </div>
                        <div id="nickname_error" class="error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="surname_input" class="cols-sm-2 control-label">Surname</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="surname" id="surname_input" value="<?php echo $user['surname']?>"/>
                        </div>
                        <div id="surname_error" class="error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="cols-sm-2 control-label">Gender</label>
                    <div class="cols-sm-10">
                        <div id="gender_input" class="input-group">
                            <label class="radio">Man
                                <input type="radio" name="gender" value="man" <?php if ($user['gender'] == 'man') echo 'checked'?>>
                                <span class="checkmark"></span>
                            </label>
                            <label class="radio">Woman
                                <input type="radio" name="gender" value="woman" <?php if ($user['gender'] == 'woman') echo 'checked'?>>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div id="gender_error" class="error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="birthdate_input" class="cols-sm-2 control-label">Birthdate</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                            <input type="date" class="form-control" name="birthdate" id="birthdate_input" value="<?php echo $user['birthdate']?>"/>
                        </div>
                    </div>
                    <div id="birthdate_error" class="error"></div>
                </div>
                <div class="form-group">
                    <label for="role_input" class="cols-sm-2 control-label">Role</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                            <select id="role_input" name="role" class="form-control">
                                <option value="">Choose role</option>
                                <option <?php if ($user['role'] == 'admin') echo 'selected'?>>admin</option>
                                <option <?php if ($user['role'] == 'user') echo 'selected'?>>user</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="role_error" class="error"></div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Update!</button>
                </div>

                <?php endforeach;
                    endif;?>
            </form>
        </div>
    </div>
</div>

<script>
    function updateUser(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "processes.php",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            if (message.login_exist) {
                $("#login_input").addClass('error-input');
                $("#login_error").html(message.login_exist);
            } else if (message.login_empty) {
                $("#login_input").addClass('error-input');
                $("#login_error").html(message.login_empty);
            } else if (message.login_regex) {
                $("#login_input").addClass('error-input');
                $("#login_error").html(message.login_regex);
            } else {
                $("#login_input").removeClass("error-input");
                $("#login_error").html("");
            }

            if (message.password_mismatch) {
                $("#password_input").addClass('error-input');
                $("#check_password_input").addClass('error-input');
                $("#password_error").html(message.password_mismatch);
            } else {
                $("#password_input").removeClass("error-input");
                $("#check_password_input").removeClass("error-input");
                $("#password_error").html("");
            }

            var inputs = ['nickname', 'surname', 'gender', 'birthdate', 'role'];

            for (var i = 0; i < inputs.length; i++) {
                if (message[inputs[i] + "_empty"]) {
                    $("#" + inputs[i] + "_input").addClass('error-input');
                    $("#" + inputs[i] + "_error").html(message[inputs[i] + "_empty"]);
                } else {
                    $("#" + inputs[i] + "_input").removeClass("error-input");
                    $("#" + inputs[i] + "_error").html("");
                }
            }

            if (message.success) {
                alert(message.success);
                location.href = "list.php";
            }
        });
    }
</script>
</body>
</html>