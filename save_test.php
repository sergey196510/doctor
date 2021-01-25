<?php

$id = $_POST['id'];
$str = $_POST['test'];

file_put_contents('data/'.$id.'.txt', $str);

header('Location: checktest.php?id='.$id);

?>