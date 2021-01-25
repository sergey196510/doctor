<?php

session_start();

setcookie('login', '');

foreach ($_SESSION as $var) {
    unset($var);
}
session_destroy();
header("Location: index.php");
exit;

?>
