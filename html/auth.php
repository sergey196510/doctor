<?php

#
# HTML view and modify calls database
#
# Copyright: Sergey I. Lang
# 2018-2021
#

include 'view/top.phtml';
include 'funcs/funcs.php';

$logfile = "/tmp/doctor.log";

file_put_contents($logfile, "Начало авторизации...\n", FILE_APPEND);

function get_user($user)
{
    global $passwd_file;
    global $logfile;

    $arr = read_passwords();
    foreach ($arr as $data) {
        if ($data[0] == $user) {
            return $data;
        }
    }

    return false;
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
}

if (isset($username) && isset($password)) {
    file_put_contents($logfile, $username.":".$password."\n", FILE_APPEND);
    if (($data = get_user($username)) !== false) {
#	file_put_contents($logfile, "2\n", FILE_APPEND);
        $login = $data[0];
        $pass  = $data[1];
        $fullname = $data[2];
        $privs = $data[3];
        if (password_verify($password, $pass) == false) {
#	    file_put_contents($logfile, "3\n", FILE_APPEND);
            syslog(LOG_WARNING, "Unknown username or password: $username");
            file_put_contents($logfile, "Неверное имя пользователя или пароль\n", FILE_APPEND);
        }
        else {
#	    file_put_contents($logfile, "4\n", FILE_APPEND);
            syslog(LOG_INFO, "User is active: $login");
            $_SESSION['username'] = $fullname;
            $_SESSION['login']    = $login;
            setcookie('login', $login);
	    header("Location: index.php");
	    exit;
        }
    }
    else {
#	file_put_contents($logfile, "5\n", FILE_APPEND);
        syslog(LOG_WARNING, "Unknown username or password: $username");
        file_put_contents($logfile, "имя пользователя не найдено\n", FILE_APPEND);
    }
    echo "Login or password incorrect";
#    header("Location: auth.php");
#    exit;
}

include 'view/auth.phtml';
include 'view/footer.phtml';
?>
