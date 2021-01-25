<?php

require 'funcs/funcs.php';

if (isset($_GET['id'])) {
    $data['id'] = $_GET['id'];
    $data['str'] = file_get_contents('data/'.$data['id'].'.txt');
    generate('test_edit.phtml', 'template.php', $data);
}

?>