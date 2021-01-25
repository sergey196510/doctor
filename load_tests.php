<?php

include 'config/config.php';
include 'funcs/funcs.php';

session_start();

if (!isset($_SESSION['login']))
    exit;

if (isset($_SESSION['tests']))
    unset($_SESSION['tests']);
if (isset($_POST['tests']))
    unset($_POST['tests']);

if (!isset($_GET['file']) or !isset($_GET['id'])) {
    exit;
}
$file = $_GET['file'];
$id = $_GET['id'];

$tests = read_tests();
$data = read_category($data_files."/".$file.".txt");

$list_tests = '';
foreach ($data[$id][1] as $d) {
    $fe = '';
    if (!file_exists($data_files."/".$d.".txt")) $fe = 'disabled';
    $list_tests .= "<span class='test'><label><input type=checkbox name=tests[] value=".$d." ".$fe.">".$tests[$d]."</input></label></span>";
}
include 'view/tests.phtml';
