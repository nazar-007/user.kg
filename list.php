<?php

session_start();
if (!$_SESSION['id'] && !$_SESSION['role']) {
    header("Location: /");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All users</title>
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
            <ul class="nav navbar-nav">
                <li>
                    <a>
                        <form action="list.php" method="get">
                            <select name="order_by" class="btn-warning" onchange="this.form.submit()">
                                <option value="">Choose order by...</option>
                                <option>id asc</option>
                                <option>id desc</option>
                                <option>login asc</option>
                                <option>login desc</option>
                                <option>nickname asc</option>
                                <option>nickname desc</option>
                                <option>surname asc</option>
                                <option>surname desc</option>
                                <option>birthdate asc</option>
                                <option>birthdate desc</option>
                            </select>
                        </form>
                    </a>
                </li>
                <li>
                    <a>
                        <form action="list.php" method="get">
                            <select name="limit" class="btn-primary" onchange="this.form.submit()">
                                <option>Choose limit</option>
                                <option>5</option>
                                <option>10</option>
                                <option>20</option>
                                <option>50</option>
                            </select>
                        </form>
                    </a>
                </li>
            </ul>
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

    <h1 class="centered">All users</h1>

    <?php if ($_SESSION['role'] == 'admin') echo '<button class="btn btn-success center-block" data-toggle="modal" data-target="#insertUser">Create new user</button><br>';?>

    <table class="table table-striped" border="3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Nickname</th>
                <th>Surname</th>
                <th>Birthdate</th>
                <th style="text-align: center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'config.php';

            $page = $_GET['page'] != null ? $_GET['page'] : 1;
            $order_by = $_GET['order_by'] != null ? $_GET['order_by'] : 'id asc';
            $limit = $_GET['limit'] != null ? $_GET['limit'] : 5;
            $users = $users_db->getUsers($page, $order_by, $limit);

            if (count($users) == 0) {
                echo "<h3 class='centered error'>Users not found on this page! :(</h3>";
            } else {
                foreach ($users as $user) {
                    $id = $user['id'];
                    echo "<tr class='one-user-$id'>
                    <td>" . $user['id'] . "</td>
                    <td>" . $user['login'] . "</td>
                    <td>" . $user['nickname'] . "</td>
                    <td>" . $user['surname'] . "</td>
                    <td>" . $user['birthdate'] . "</td>
                    <td>
                        <button class='btn-info btn-radius'>
                            <a href='one_user.php?id=" . $user['id'] . "'>Show</a>
                        </button>";
                    if ($_SESSION['role'] == 'admin') {
                        echo "<button class='btn-danger btn-radius' onclick='deletePressUser(this)' data-toggle='modal' data-target='#deleteUser' data-id='" . $user['id'] . "' data-login='" . $user['login'] . "'>Delete</button>
                        <button class='btn-warning btn-radius'>
                            <a href='update_user.php?id=$id'>Update</a>
                        </button>";
                            }
                    echo "</td>
                </tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <ul class="pagination pull-right">
        <?php
        $count_pages = $users_db->getCountPages($limit);
        for ($i = 1; $i <= $count_pages; $i++) {
            if ($count_pages > 1) {
                if ($i == $page) {
                    echo "<li class='active'>";
                } else {
                    echo "<li>";
                }
                $order_by = str_replace(" ", "+", $order_by);
                echo "<a href='list.php?page=" . $i . "&order_by=" . $order_by . "&limit=" . $limit . "'>" . $i . "</a>
            </li>";
            }
        }
        ?>
    </ul>

</div>

<div class="modal fade" id="insertUser" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Inserting user</h4>
            </div>
            <div class="modal-body">
                <div class="row main">
                    <div class="main-login main-center">
                        <form method="post" action="javascript:void(0)" onsubmit="insertUser(this)">
                            <input type="hidden" name="action" value="insert_user">
                            <div class="form-group">
                                <label for="login_input" class="cols-sm-2 control-label">Login</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="login" id="login_input" placeholder="Enter login"/>
                                    </div>
                                    <div id="login_error" class="error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_input" class="cols-sm-2 control-label">Password</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                        <input type="password" class="form-control" name="password" id="password_input"  placeholder="Enter your Password"/>
                                    </div>
                                    <div id="password_error" class="error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="check_password_input" class="cols-sm-2 control-label">Check password</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                        <input type="password" class="form-control" name="check_password" id="check_password_input"  placeholder="Enter your Password"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nickname_input" class="cols-sm-2 control-label">Nickname</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="nickname" id="nickname_input" placeholder="Enter nickname"/>
                                    </div>
                                    <div id="nickname_error" class="error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="surname_input" class="cols-sm-2 control-label">Surname</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="surname" id="surname_input" placeholder="Enter surname"/>
                                    </div>
                                    <div id="surname_error" class="error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="cols-sm-2 control-label">Gender</label>
                                <div class="cols-sm-10">
                                    <div id="gender_input" class="input-group">
                                        <label class="radio">Man
                                            <input type="radio" name="gender" value="man">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radio">Woman
                                            <input type="radio" name="gender" value="woman">
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
                                        <input type="date" class="form-control" name="birthdate" id="birthdate_input"/>
                                    </div>
                                    <div id="birthdate_error" class="error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="role_input" class="cols-sm-2 control-label">Role</label>
                                <div class="cols-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                                        <select id="role_input" name="role" class="form-control">
                                            <option value="">Choose role</option>
                                            <option>admin</option>
                                            <option>user</option>
                                        </select>
                                    </div>
                                    <div id="role_error" class="error"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Register!</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUser" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Deleting user</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" onsubmit="deleteUser(this)">
                    <h3 class="delete_question"></h3>
                    <div class="form-group">
                        <input type="hidden" name="action" value="delete_user">
                        <input class="delete_id" name="id" type="hidden">
                        <input class="delete_login" name="login" type="hidden">
                        <div class="centered">
                            <button class="btn btn-primary" type="submit">Yes, delete!</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function insertUser(context) {
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

            if (message.password_empty) {
                $("#password_input").addClass('error-input');
                $("#password_error").html(message.password_empty);
            } else if (message.password_mismatch) {
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
                location.reload(true);
            }
        });
    }

    function deletePressUser(context) {
        var id = context.getAttribute('data-id');
        var login = context.getAttribute('data-login');
        $(".delete_id").val(id);
        $(".delete_login").val(login);
        $(".delete_question").html("Do you really want to delete user with login " + login + " ?");
    }

    function deleteUser(context) {
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
            $("#deleteUser").trigger('click');
            if (message.user_error) {
                alert(message.user_error);
            } else {
                $(".one-user-" + message.id).remove();
            }
        });
    }
</script>
</body>
</html>