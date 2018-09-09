<?php

session_start();
if (!$_SESSION['id']) {
    header("Location: /");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>One user</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/one_user.css">
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


    <div class="row text-center">
        <?php
        include 'config.php';

        $id = $_GET['id'];

        $one_user = $users_db->getOneUserById($id);

        if (count($one_user) == 0) :?>
            <h3 class='centered'>Sorry, but user with id <?php echo $id?> does not exist! :(</h3>
        <?php else :
        foreach ($one_user as $user) :?>
            <h3>One user</h3>
        <div class="col-md-12" style="margin-top: 20px;">
            <div class="pricing-table">
                <div class="panel panel-primary" style="border: none;">
                    <div class="controle-header panel-heading panel-heading-landing">
                        <h1 class="panel-title panel-title-landing">
                            <?php echo $user['login']?>
                        </h1>
                    </div>
                    <div class="panel-body panel-body-landing">
                        <table class="table">
                            <tr>
                                <td width="50px">id</td>
                                <td><?php echo $user['id'] ?></td>
                            </tr>
                            <tr>
                                <td width="50px">login</td>
                                <td><?php echo $user['login'] ?></td>
                            </tr>
                            <tr>
                                <td width="50px">nickname</td>
                                <td><?php echo $user['nickname'] ?></td>
                            </tr>
                            <tr>
                                <td width="50px">surname</td>
                                <td><?php echo $user['surname'] ?></td>
                            </tr>
                            <tr>
                                <td width="50px">gender</td>
                                <td><?php echo $user['gender'] ?></td>
                            </tr>
                            <tr>
                                <td width="50px">birthdate</td>
                                <td><?php echo $user['birthdate'] ?></td>
                            </tr>
                            <tr>
                                <td width="50px">role</td>
                                <td><?php echo $user['role'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php if ($_SESSION['role'] == 'admin') {
                        echo "<div class='controle-panel-heading panel-heading panel-heading-landing-box'>
                            <h4>Actions for admin</h4>
                            <button onclick='deletePressUser(this)' class='btn btn-danger' data-id='" . $user['id'] . "' data-login='" . $user['login'] . "' data-toggle='modal' data-target='#deleteUser'>Delete</button>
                            <button class='btn btn-warning'>
                                <a href='update_user.php?id=" . $user['id'] . "'>Update</a>
                            </button>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php endforeach;
        endif;
        ?>
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
                            <button class="btn btn-primary" type="submit">Yes, delete</button>
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
            if (message.user_error) {
                alert(message.user_error);
            } else {
                location.href = 'list.php';
            }
        });
    }
</script>
</body>
</html>