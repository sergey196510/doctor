<?php

function generate($myview, $template, $data = 0)
{
    require $template;
}

function read_tests()
{
    global $data_files;
    $tests = array();

    $filename = $data_files."/tests.txt";
    $fp = fopen($filename, 'r') or die("Can't open file ".$filename);
    while (($str = fgets($fp, 2000)) !== false) {
        $str = trim($str);
        list($id, $name) = explode(":", $str);
        $tests[$id] = $name;
    }
    fclose($fp);

    return $tests;
}

function read_category($filename)
{
#    $filename = $data_files."/".$file.".txt";
    if (!file_exists($filename)) {
	echo "File ".$filename." not found";
	exit;
    }

    $data = array();
    $id = 0;
    $fp = fopen($filename, 'r') or die("file ".$filename." can't open");
    while ($str = fgets($fp, 2000)) {
	$str = trim($str);
	list($id_,$name,$val) = explode(":", $str);
	$arr = explode(",", $val);
	$data[$id][0] = $name;
	$data[$id][1] = $arr;
	$id++;
    }
    fclose($fp);

    return $data;
}

function read_passwords()
{
    global $passwd_file;

    if (!file_exists($passwd_file))
	return false;

    $p = array();
    $fp = fopen($passwd_file, 'r') or die("file ".$passwd_file." can't open");
    while ($str = fgets($fp, 2000)) {
	$str = trim($str);
	$p[] = explode(":", $str);
    }
    fclose($fp);

    return $p;
}
?>
