<?php
session_start();
if ($_SESSION['id']) {
    header("Location: list.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User.kg</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
<div class="container">
    <div class="row" id="pwd-container">
        <div class="col-md-4"></div>

        <div class="col-md-4">
            <section class="login-form">
                <form method="post" action="javascript:void(0)" onsubmit="authorization(this)" role="login">
                    <input type="hidden" name="action" value="login">
                    <img src="images/logo.png" class="img-responsive" alt="" />
                    <input type="text" name="login" placeholder="Enter your login" class="form-control input-lg" id="login_input" />
                    <div id="login_error" class="error"></div>
                    <input type="password" name="password" class="form-control input-lg" id="password_input" placeholder="Enter your password" />
                    <div id="password_error" class="error"></div>
                    <div id="incorrect" class="error"></div>
                    <div class="pwstrength_viewport_progress"></div>
                    <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
                </form>
            </section>
        </div>
        <div class="col-md-4"></div>
    </div>

    <script>
        function authorization(context) {
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
                if (message.incorrect || message.login_empty || message.password_empty) {
                    $("#login_input").addClass('error-input');
                    $("#password_input").addClass('error-input');

                    if (message.incorrect) {
                        $("#incorrect").html(message.incorrect);
                    } else {
                        $("#incorrect").html('');
                    }

                    if (message.login_empty) {
                        $("#login_error").html(message.login_empty);
                    } else {
                        $("#login_error").html('');
                        $("#login_input").removeClass('error-input');
                    }

                    if (message.password_empty) {
                        $("#password_error").html(message.password_empty);
                    } else {
                        $("#password_error").html('');
                        $("#password_input").removeClass('error-input');
                    }
                }
                if (message.success) {
                    location.href = "list.php";
                }
            });
        }
    </script>

</body>
</html>