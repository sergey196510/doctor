<?php

include 'view/top.phtml';
include 'funcs/funcs.php';
include 'funcs/read_tests.php';

if (!isset($_SESSION['login']))
    exit;

$list = read_tests();
$testlist = '';

foreach ($list as $key => $value) {
    $act  = '';
    if (file_exists('data/'.$key.'.txt')) {
	$str = read_data1('data/'.$key.'.txt');
	$act = "<div style='display: inline-block; background: #fff; outline: 1px solid;'>";
	$act .= "<a href=test_edit.php?id=".$key.">Редактировать</a>";
	$act .= "</div>";
	$act .= "<div style='display: inline-block; background: #fff; outline: 1px solid;'>";
	$act .= "<a href=checktest.php?id=".$key.">Проверить</a>";
	$act .= "</div>";
	$act .= "<div style='display: inline-block; background: #fff; outline: 1px solid;'>";
	$act .= "<a href=deletetest.php?id=".$key.">Удалить</a>";
	$act .= "</div>";
	$act .= "<div style='display: inline-block; background: #fff; outline: 1px solid;'>";
	$act .= "<a href=loadfileform.php?id=".$key.">Загрузить</a>";
	$act .= "</div>";
	if (strstr($str, 'Line'))
	    $act .= 'x';
	$act .= "</div>";
    }
    else {
	$act .= "<div style='display: inline-block; background: #fff; outline: 1px solid;'>";
	$act .= "<a href=loadfileform.php?id=".$key.">Загрузить</a>";
	$act .= "</div>";
    }
#    $act = "<div class='testact' id='".$key."'></div>";
    $testlist .= "<tr><td align=right>".$key."</td><td align=left>".$value."</td><td align=right>".$act."</td></tr>";
}
include 'view/testlist.phtml';

include 'view/footer.phtml';

?>
