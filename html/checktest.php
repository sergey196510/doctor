<?php

include 'config/config.php';
include 'funcs/funcs.php';
include 'funcs/read_tests.php';

if (isset($_GET['id'])) {
    $data['id'] = $_GET['id'];
    $data['str'] = read_data1('data/'.$_GET['id'].'.txt');
    generate('checktest.phtml', 'template.php', $data);
}
