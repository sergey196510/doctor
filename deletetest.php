<?php

include 'config/config.php';
include 'funcs/funcs.php';

session_start();

if (!isset($_SESSION['login']))
    exit;

if (isset($_GET['id'])) {
    unlink('data/'.$_GET['id'].'.txt');
    header("Location: testlist.php");
    exit;
}
