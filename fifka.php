<?php

include 'funcs/read_tests.php';

$id = $_GET['id'];

$num = 0;
read_data1("data/".$id.".txt");
$recommends = $_SESSION['recommends'];

?>

<div style='outline: 1px solid;'>
<?php echo $recommends ?>
</div>
