<?php

include 'view/top.phtml';
include 'funcs/funcs.php';
include 'funcs/read_tests.php';

if (!isset($_SESSION['login']))
    exit;

$list = read_tests();

if (isset($_GET['id']))
    $key = $_GET['id'];
else
    exit;

read_data1('data/'.$key.'.txt');
$descr = $_SESSION['description'];
$n = $_SESSION['names'];
$results = $_SESSION['results'];
$testnumber = $key;
$testname = $list[$key];

$names = '<table><tr><th>Диагнозы</th><th>Симптомы</th></tr>';
foreach ($n as $key => $val) {
    if ($key < 100) {
	$names .= "<tr><td><div style='width: 400px; outline: 1px solid; background: #fff;'>".$val.'</div></td><td>';
	foreach ($results[$key] as $k => $v) {
	    $names .= "<div style='outline: 1px solid; background: #fff;'>".$n[$k].'</div>';
	}
	$names .= '</td></tr>';
    }
}
$names .= '</table>';

include 'view/testedit.phtml';

include 'view/footer.phtml';

?>
