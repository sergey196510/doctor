<?php

include 'config/config.php';
include 'funcs/funcs.php';

include 'view/top.phtml';

if (!isset($_SESSION['login']))
    exit;

$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

$tests = read_tests();
$testname = $tests[$id];

include 'view/loadfileform.phtml';

include 'view/footer.phtml';
