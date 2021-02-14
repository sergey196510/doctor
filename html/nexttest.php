<?php

session_start();

if (!isset($_SESSION['login']))
    header('Location: index.php');

$_SESSION['test_current'] += 1;
header('Location: testrun.php');
