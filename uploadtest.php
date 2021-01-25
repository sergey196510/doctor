<?php

include 'config/config.php';
include 'funcs/funcs.php';
include 'funcs/read_tests.php';

session_start();

if (!isset($_SESSION['login']))
    exit;

var_dump($_POST);

if (isset($_FILES['userfile'])) {
    $filename = 'data/'.$_FILES['userfile']['name'];
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $filename)) {
	$str = file_get_contents($filename);
	$str = iconv($_POST['coding'], 'UTF-8', $str);
	file_put_contents($filename, $str);
#	$str = read_data1($filename);
#	include 'view/checktest.phtml';
	header("Location: checktest.php?id=".$_POST['id']);
    }
    else {
	echo 'Ошибка при передаче файла: '.$_FILES['userfile']['name'];
    }
    exit;
}
