<?php

include 'funcs/read_tests.php';

function delete_without($results, $item)
{
    foreach ($results as $key => $value) {
	if (!isset($value[$item])) {
	    unset($results[$key]);
	}
	else {
	    unset($results[$key][$item]);
	}
    }

    return $results;
}

function delete_with($results, $item)
{
    foreach ($results as $key => $value) {
	if (isset($value[$item]))
	    unset($results[$key]);
    }

    return $results;
}

function work_item($results)
{
    global $num;

#    print_r($results);

    if (count($results) == 1 and count(current($results)) == 0) {
	echo key(current($results))."\n";
    }

    $sympt = current($results);
    $item = key($sympt);
    for ($i = 0; $i < $num; $i++)
	echo " ";
    echo $item." -> ";

    $with = delete_without($results, $item);
#    print_r($with);
    if (count($with) == 1 and count(current($with)) > 0) {
	echo "with\n";
	$num += 2;
	work_item($with);
    }

    $without = delete_with($results, $item);
#    print_r($without);
    if (count($without) > 0) {
	echo "\n";
	echo "without\n";
	$num -= 2;
	work_item($without);
    }
}

$num = 0;
read_data1('data/1.txt');
$results = $_SESSION['results'];
work_item($results);

?>
