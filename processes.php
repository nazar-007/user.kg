<?php
include_once 'config.php';

$action = $_POST['action'];

if ($action == 'login') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $messages = array();

    $num_rows = $users_db->authorization($login, md5($password));

    if ($num_rows == 1) {
        session_start();
        $id = $users_db->getColumnByLoginAndPassword('id', $login, md5($password));
        $role = $users_db->getColumnByLoginAndPassword('role', $login, md5($password));
        $_SESSION['id'] = $id;
        $_SESSION['role'] = $role;
        $messages['success'] = "You are logged in your account.";
    } else {
        if (empty($login) || empty($password)) {
            if (empty($login)) {
                $messages['login_empty'] = 'Login is empty. Set your login.';
            }
            if (empty($password)) {
                $messages['password_empty'] = 'Password is empty. Set your password';
            }
        } else {
            $messages['incorrect'] = "Incorrect login or password.";
            $messages['login'] = $login;
        }
    }
    $json_messages = json_encode($messages);
    exit($json_messages);
}

if ($action == 'logout') {
    session_start();
    session_destroy();
    header("Location: /");
}

if ($action == 'insert_user') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $check_password = $_POST['check_password'];
    $nickname = $_POST['nickname'];
    $surname = $_POST['surname'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];

    $num_rows = $users_db->getNumRowsByLogin($login);

    $messages = array();

    if ($num_rows > 0 || !preg_match('|^[a-zA-Z0-9]*$|', $login) || $password != $check_password || empty($login) || empty($password)
        || empty($nickname) || empty($surname) || empty($gender) || empty($birthdate) || empty($role)) {
        if ($num_rows > 0) {
            $messages['login_exist'] = "This login already exists. Please, enter another login";
        }
        if(!preg_match('|^[a-zA-Z0-9]*$|', $login)) {
            $messages['login_regex'] = 'There are unacceptable symbols in your login. You can use just numbers and English letters!';
        }
        if ($password != $check_password) {
            $messages['password_mismatch'] = "Passwords mismatch.";
        }
        if (empty($login)) {
            $messages['login_empty'] = "Login is empty. Please, set some symbols.";
        }
        if (empty($password)) {
            $messages['password_empty'] = "Password is empty. Please, set some symbols.";
        }
        if (empty($nickname)) {
            $messages['nickname_empty'] = "Nickname is empty. Please, set some symbols.";
        }
        if (empty($surname)) {
            $messages['surname_empty'] = "Surname is empty. Please, set some symbols.";
        }
        if (empty($gender)) {
            $messages['gender_empty'] = "Gender is not checked. Please, choose your gender.";
        }
        if (empty($birthdate)) {
            $messages['birthdate_empty'] = "Birthdate is not checked. Please, choose your birthdate.";
        }
        if (empty($role)) {
            $messages['role_empty'] = "Role is not checked. Please, choose your role.";
        }
    } else {
        $data = array(
            'login' => $login,
            'password' => md5($password),
            'nickname' => $nickname,
            'surname' => $surname,
            'gender' => $gender,
            'birthdate' => $birthdate,
            'role' => $role
        );
        $users_db->insertUser($data);

        $messages['success'] = "User is successfully inserted!";
    }

    $json_messages = json_encode($messages);
    exit($json_messages);
}

if ($action == 'delete_user') {
    $id = $_POST['id'];
    $login = $_POST['login'];

    $num_rows = $users_db->getNumRowsByLogin($login);
    $messages = array();

    if ($num_rows > 0 && !empty($id)) {
        $users_db->deleteUserById($id);
        $messages['id'] = $id;
        $messages['success'] = "User is successfully deleted!";
    } else {
        $messages['user_error'] = "Sorry, but login does not exist or id not found. Please, check it!";
    }

    $json_messages = json_encode($messages);
    exit($json_messages);
}

if ($action == 'update_user') {
    $id = $_POST['id'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $check_password = $_POST['check_password'];
    $current_login = $users_db->getColumnById('login', $id);
    $nickname = $_POST['nickname'];
    $surname = $_POST['surname'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];

    $num_rows = $users_db->getNumRowsByLoginAndCurrentLogin($login, $current_login);

    $messages = array();

    if ($num_rows > 0 || !preg_match('|^[a-zA-Z0-9]*$|', $login) || $password != $check_password || empty($id) || empty($login)
        || empty($nickname) || empty($surname) || empty($gender) || empty($birthdate) || empty($role)) {
        if ($num_rows > 0) {
            $messages['login_exist'] = "This login already exists. Please, enter another login";
        }
        if (!preg_match('|^[a-zA-Z0-9]*$|', $login)) {
            $messages['login_regex'] = 'There are unacceptable symbols in your login. You can use just numbers and English letters!';
        }
        if ($password != $check_password) {
            $messages['password_mismatch'] = "Passwords mismatch!";
        }
        if (empty($id)) {
            $messages['id_empty'] = "ID not found";
        }
        if (empty($login)) {
            $messages['login_empty'] = "Login is empty. Please, set some symbols.";
        }
        if (empty($nickname)) {
            $messages['nickname_empty'] = "Nickname is empty. Please, set some symbols.";
        }
        if (empty($surname)) {
            $messages['surname_empty'] = "Surname is empty. Please, set some symbols.";
        }
        if (empty($gender)) {
            $messages['gender_empty'] = "Gender is not checked. Please, choose your gender.";
        }
        if (empty($birthdate)) {
            $messages['birthdate_empty'] = "Birthdate is not checked. Please, choose your birthdate.";
        }
        if (empty($role)) {
            $messages['role_empty'] = "Role is not checked. Please, choose your role.";
        }
    } else {
        $current_password = $users_db->getColumnById('password', $id);
        if (empty($password)) {
            $password = $current_password;
        } else {
            $password = md5($_POST['password']);
        }

        $data = array(
            'login' => $login,
            'password' => $password,
            'nickname' => $nickname,
            'surname' => $surname,
            'gender' => $gender,
            'birthdate' => $birthdate,
            'role' => $role
        );
        $users_db->updateUserById($id, $data);
        $messages['success'] = "User is successfully updated!";
    }

    $json_messages = json_encode($messages);
    exit($json_messages);
}