<?php

session_start();

require 'funcs/read_tests.php';

for ($i = 1; $i < 148; $i++) {
    read_data1('data/'.$i.'.txt');
    $results = $_SESSION['results'];
    echo 'test '.$i.': ';
    $flag = 0;
    foreach ($results as $key => $value) {
	if (!in_array(1, $value))
	    $flag = 1;
    }
    echo ($flag == 1) ? "Ok" : "Bad" ;
    echo "\n";
}

?>
