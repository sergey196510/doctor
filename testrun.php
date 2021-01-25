<?php

function dump($func, $var) {
        echo "<pre>".$func.": ".$var."<p>";
        var_dump($var);
        echo "</pre>";
}

function form_request($request, $data, $tst)
{
        global $_SESSION;
        global $_POST;
        $tests_list = read_tests();
        $test_name = $tests_list[$tst];

	$descr = $_SESSION['description'];
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $names = (isset($_SESSION['names'])) ? $_SESSION['names'] : array();
        $recommends = (isset($_SESSION['recommends'])) ? $_SESSION['recommends'] : '';

	$simptoms = "";
        foreach ($data as $idx => $val) {
#            list($idx, $val) = explode(",", $value);
	    $color = ($val == 1) ? '#000' : '#555';
            $simptoms .= "<div style='white-space: normal; color: ".$color.";'>";
            $simptoms .=  $names[$idx]." - ";
            $simptoms .=  ($val == 1) ? "да" : "нет";
            $simptoms .=  "</div>";
            $simptoms .=  "<input type=hidden name=\"data[".$idx."]\" value='".$val."'>";
        }
        $question = $names[$request];

        include 'view/testrun.phtml';
}

function calc_request($results, $data)
{
        $req = array();

        # подсчет количества вхождений элемента
        foreach ($results as $key => $value) {
                foreach ($value as $key => $val) {
        	    if ($val == 0)
        		continue;
                    $flag = false;
                    foreach ($data as $i => $d) {
#                        list($i, $v) = explode(",", $d);
                        if ($i == $key) {
                            $flag = true;
                            break;
                        }
                    }
                    if ($flag == false) {
                        if (isset($req[$key])) {
                            $req[$key] += 1;
                        }
                        else {
                            $req[$key] = 1;
                        }
                    }
                }
        }

#	var_dump($req);

        # выборка элемента с мин кол-вом вхождений
        $minimum = min($req);
        foreach ($req as $key => $value) {
                if ($value == $minimum) {
                        return $key;
                }
        }

        return "Unknown";
}

# прореживание массива в соответствии с выбранными атрибутами
function copy_array($results, $data)
{
    $arr = array();

#    dump(__FUNCTION__, $data);
#    dump(__FUNCTION__, $results);

    foreach ($data as $idx => $val) {
#        list($idx, $val) = explode(",", $value);
	foreach ($results as $rkey => $rval) {
            if ($val == 0) {
#		dump(__FUNCTION__, $rval);
        	if (isset($rval[$idx]) and $rval[$idx] != $val) {
                    unset($results[$rkey]);
        	}
            }
            else if ($val == 1) {
        	if (!isset($rval[$idx]) or (isset($rval[$idx]) and $rval[$idx] != $val)) {
                    unset($results[$rkey]);
                }
            }
	}
    }

#	dump(__FUNCTION__, $results);

    return $results;
}

function check_diagnose($results, $data)
{
#    dump(__FUNCTION__, $data);
#    dump(__FUNCTION__, $results);

    if (count($data) == 0)
        return false;

    if (count($results) == 1 and count($results[key($results)]) == 0) {
        return key($results);
    }

    foreach ($results as $key => $value) {
        if (count($value) == 0)
            continue;

        $flag = false;
        foreach ($value as $k => $v) {
            if (!isset($data[$k]) or $data[$k] != $v) {
                $flag = true;
                break;
            }
        }
        if ($flag == false)
            return $key;
    }

    return false;
}

function check_diagnose2($results, $data)
{
    dump(__FUNCTION__, $data);
    dump(__FUNCTION__, $results);

    $n = 0;
    foreach ($results as $key => $value) {
	$tmp = $value;

	dump(__FUNCTION__, $tmp);

	$flag = false;
	foreach ($tmp as $k => $d) {
	    if (!isset($data[$k]) or $data[$k] != $d) {
		$flag = true;
		break;
#		unset($tmp[$k]);
#		dump(__FUNCTION__, $tmp);
	    }
#	    if (empty($tmp) or !in_array(1,$tmp))
#		return $key;
	    $n += 1;
	}
	if (!$flag)
	    return $key;
#	dump(__FUNCTION__, $arr);
    }
    if ($n == 0)
	return $key;

#	echo "return ''";
    return false;
}

function view_result($data, $res, $tst)
{
	global $_SESSION;
	$descr = $_SESSION['description'];
	$names = $_SESSION['names'];
	$tests = $_SESSION['tests'];
	$test_current = $_SESSION['test_current'];
        $recommends = (isset($_SESSION['recommends'])) ? $_SESSION['recommends'] : '';

        $tests_list = read_tests();
        $test_name = $tests_list[$tst];
	$simptoms = "";
	foreach ($data as $idx => $val) {
#		list($idx, $val) = explode(",", $value);
		$color = ($val == 1) ? '#000' : '#555';
		$simptoms .= "<div style='color: ".$color.";'>";
		$simptoms .=  $names[$idx]." - ";
		$simptoms .=  ($val == 1) ? "да" : "нет";
		$simptoms .=  "</div>";
	}
	$diagnose = $names[$res];
#	echo "<pre>";
#	var_dump($names);
#	var_dump($diagnose);
#	echo "</pre>";
	$button_next_test = "";
	if (count($tests) > 1 and $test_current < count($tests)-1) {
	    $button_next_test = "<button action=submit formaction=nexttest.php>Следующий тест</button>";
#	    $_SESSION['test_current'] = $test_current+1;
	}
	include 'view/testrun_result.phtml';
}

function view_fail()
{
	include 'view/testrun_fail.phtml';
}

function run_tree($file)
{
	global $_SESSION;
	global $_POST;
	global $data_files;

	$data_file = $data_files."/".$file.".txt";
	if (!file_exists($data_file)) {
		echo "<div style='outline: 1px solid; display: inline-block; padding: 5px;'>";
		echo "Тест ".$file." отсутствует.";
		echo "</div>";
		echo "<div>";
		echo "<form><button action='submit' formaction='testselect.php'>Выбор теста</button></form>";
		echo "</div>";
		return false;
	}

	if (isset($_POST['data'])) {
		$data = $_POST['data'];
		if (!isset($_SESSION['results']))
			read_data1($data_file);
		$results = $_SESSION['results'];
		# прореживание массива данных
		$results = copy_array($results, $data);
		$_SESSION['results'] = $results;
#           write_data($filename, $results);
	}
	else {
		$data = array();
		if (isset($_SESSION['results'])) unset($_SESSION['results']);
		read_data1($data_file);
		$results = $_SESSION['results'];
#		var_dump($results);
	}

	if (empty($results)) {
		view_fail();
		return true;
	}
#	if (count($results) == 1 and count($results[key($results)]) == 0) {
#		$res = key($results);
#		view_result($data, $res, $file);
#		view_fail();
#		return true;
#	}

	$res = check_diagnose($results, $data);
	if ($res !== false) {
		if (strpos($res, ",") !== false) {
			list($a,$b) = explode(",", $res);
			unset($_POST['data']);
			return run_tree($b);
		}
		else {
			view_result($data, $res, $file);
			return true;
		}
	}
	else {
		$request = calc_request($results, $data);
		form_request($request, $data, $file);
		return true;
	}
	
	return false;
}

include 'view/top.phtml';
include 'funcs/funcs.php';
include 'funcs/read_tests.php';

if (!isset($_SESSION['login']))
	header('Location: index.php');

if (isset($_POST['tests'])) {
	$_SESSION['tests'] = $_POST['tests'];
	$_SESSION['test_current'] = 0;
}
if (isset($_SESSION['tests'])) {
	$tests = $_SESSION['tests'];
	$test_current = isset($_SESSION['test_current']) ? $_SESSION['test_current'] : 0;
#	var_dump($tests);
#	var_dump($test_current);
	run_tree((isset($_GET['id'])) ? $_GET['id'] : $tests[$test_current]);
}
else {
	header('Location: testselect2.php');
}

include 'view/footer.phtml';

?>
