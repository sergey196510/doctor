<?php

if (!isset($_POST['login'])) {
    echo "Пустое значение";
    exit;
}
$login = $_POST['login'];

include 'config/config.php';
include 'funcs/funcs.php';

$arr = read_passwords();
foreach ($arr as $val) {
    if ($val[0] == $login) {
	echo "Такой логин существует. Выберите другой";
	fclose($fp);
	exit;
    }
}

echo "Ok";
exit;

?>
