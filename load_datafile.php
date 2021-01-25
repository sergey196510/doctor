<?php

include 'config/config.php';
include 'funcs/funcs.php';

session_start();

if (!isset($_SESSION['login']))
    exit;

if (!isset($_GET['file'])) {
    exit;
}
$catfile = $_GET['file'];

$data = read_category($data_files."/".$catfile.".txt");

$names = "";
foreach ($data as $k => $v) {
#    $names .= "<option value=".$k.">".$v[0]."</option>";
    $names .= "<div>";
    $names .= "<label><input class='tst' name='cat' type='radio' value='".$k."'>";
    $names .= "".$v[0]."</label>";
    $names .= "</div>";
}

include 'view/datafile.phtml';
