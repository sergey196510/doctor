<?php

#
# HTML view and modify calls database
#
# Copyright Sergey I. Lang
# 2018
#

function read_parent_data()
{
	global $_POST;
	global $data_files;
	$arr = array();

	$fp = fopen($data_files."/data0.txt", "r");

	if ($fp) {
		while (($s = fgets($fp, 1000)) !== false) {
			$str = trim($s);
			$str = explode(":", $str);
			$arr[] = $str;
		}
	}

	$id = (isset($_POST['id'])) ? $_POST['id'] : 0;

?>

<html><center>

<div class="comm">
<form method=POST>

<pre>
<?php

	$tests = array();
	$tt = array();
	if (isset($_POST['tests'])) {
		$tests = $_POST['tests'];
	}
	$tests = array_unique($tests);
	foreach ($tests as $val) {
		echo "<input type=hidden name=tests[] value=".$val.">";
	}

	var_dump($tests);
?>
</pre>

<div class=test_title>
<h2><?php echo $arr[$id][0]; ?></h2>
</div>
<div>
такие, как:
</div>
<div class=test_list>
<h3>
<?php
	$diags = explode(",", $arr[$id][1]);
	foreach ($diags as $val) {
		if (stripos($val, "|") !== false) list($name, $test) = explode("|", $val);
		else {
			$name = $val;
			$test = "";
		}
		$check = "";
		if ($test and in_array($test, $tests)) $check = "checked";
		$fe = "disabled";
		if (file_exists($data_files."/".$test.".txt")) $fe = "";
		echo "<p><input type=checkbox name=tests[] value=".$test." ".$check." ".$fe.">".$name."</p>";
	}
?>
</div>

<div class="actions">
<?php
if ($id > 0) {
	$v = $id-1;
	$dis = "";
}
else {
	$v = $id;
	$dis = "disabled class=disabled";
}
echo "<button type=submit name=id value=". $v ." ".$dis.">назад</button>";

if (isset($_POST['tests']) && count($_POST['tests']) > 0)
    $dis = "";
else
    $dis = "disabled class=disabled";
echo "<button type=submit name=flag value=2 ".$dis.">Начать тестирование</button>";

if ($id < count($arr)-1) {
	$v = $id+1;
	$dis = "";
}
else {
	$v = $id;
	$dis = "disabled class=disabled";
}
echo "<button type=submit name=id value=". $v ." ".$dis.">вперед</button>";
?>

</div>
</form>
</div>

<div class="comm">
<?php
#    run_tree($arr[$id][2]);
?>
</div>

</center></html>

<?php

    fclose($fp);
}

function read_data1($data_file)
{
	global $_SESSION;
	$flag = 0;
	$names = array();
	$data = array();
	$fp = fopen($data_file, "r");

	if ($fp) {
		while (($s = fgets($fp, 1000)) !== false) {
			$str = trim($s);
			if ($str == "[names]") {
				$flag = 1;
			}
			if ($str == "[diagnoses]" and $flag == 1) {
				$flag = 2;
			}
			if ($flag == 1) {
				list($id,$name) = explode();
				$names[$id] = $name;
			}
			if ($flag  == 2) {
				list($sost, $prizn) = explode(":", $str);
				$arr = explode(";", $prizn);

				foreach($arr as &$value) {
					if ($value) {
						$v = explode(",", $value);
						$data[$sost][$v[0]] = $v[1];
					}
				}
			}
		}
	}

	$_SESSION['names'] = $names;
	$_SESSION['results'] = $data;

	fclose($fp);
}

function write_data($filename, $data)
{
    $fp = fopen($filename, 'w') or die("Не удалось открыть файл для записи: ".$filename);
    if ($fp) {
        foreach ($data as $key => $value) {
            $str = $key.":";
            foreach ($value as $k => $v) {
                $str .= $k.",".$v.";";
            }
            $str = substr($str,0,-1);
            fwrite($fp, $str."\n");
        }
    }
    fclose($fp);
}

function read_data2()
{
    global $data_file;

    $data = array();
    $fp = fopen($data_file, "r");

    if ($fp) {
        while (($s = fgets($fp, 1000)) !== false) {
            $str = trim($s);
            list($sost, $prizn) = explode(":", $str);
            $arr = explode(";", $prizn);

            foreach($arr as &$value) {
#                list($prizn, $val) = explode(",", $value);
                if (isset($data[$value]) == false) {
                    $data[$value] = array();
                }
                $data[$value][] = $sost;
            }
        }
    }

    fclose($fp);
    return $data;
}

function calc_request($results, $data)
{
	$req = array();

	# подсчет количества вхождений элемента
	foreach ($results as $key => $value) {
		foreach ($value as $key => $val) {
		    $flag = false;
		    foreach ($data as $d) {
			list($i, $v) = explode(",", $d);
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

    foreach ($results as $rkey => $rval) {
        foreach ($data as $value) {
            list($idx, $val) = explode(",", $value);
            if ((isset($rval[$idx]) and $rval[$idx] == $val) or !isset($rval[$idx])) {
                if (!isset($arr[$rkey])) {
                    $arr[$rkey] = $results[$rkey];
                }
            }
        }
    }

    return $arr;
}

function check_diagnose($results, $data)
{
#	echo "<pre>";
#	var_dump($data);
#	echo "</pre>";

#	echo "<pre>";
#	var_dump($results);
#	echo "</pre>";

	foreach ($results as $key => $value) {
		$tmp = array();
		foreach ($value as $k => $v) {
			$tmp[] = $k.",".$v;
		}
#		echo "<pre>";
#		var_dump($tmp);
#		echo "</pre>";

		$arr = array_diff($tmp, $data);
		if (!count($arr)) {
			return $key;
		}

#		echo "<pre>";
#		var_dump($arr);
#		echo "</pre>";
	}

	return "";
}

function form_request($request, $data)
{
	global $_POST;

	$id = (isset($_POST['id'])) ? $_POST['id'] : 0;

?>
	<center>
	<form method=POST>
	<input type=hidden name=id value=<?php echo $id; ?>>

	Отмеченные жалобы:<br>
<?php
	foreach ($data as $value) {
	    list($idx, $val) = explode(",", $value);
	    echo "<h2>".$idx." - ";
	    echo ($val == 1) ? "да" : "нет";
	    echo "</h2><p>";
	    echo "<input type=hidden name=\"data[]\" value='".$value."'>";
	}
?>

	Пожалуйста, ответьте на вопрос:
	<table>
	<tr><td><h2><?php echo $request; ?></h2></td></tr>
	<tr><td align=center><button type=submit name=data[] value='<?php echo $request; ?>,1'>Да</button>
	<button type=submit name=data[] value='<?php echo $request; ?>,0'>Нет</button></td></tr>
	</table>

	</form></center>
<?php
}

function run_tree($file)
{
	global $_SESSION;
	global $_POST;
	global $data_files;

	$data_file = $data_files."/".$file.".txt";
	if (!file_exists($data_file)) {
	    echo "Файл ".$data_file." c тестом по этой теме отсутствует.";
	    return;
	}

	$filename = "/tmp/".$_SESSION['result_filename'].".txt";
	if (isset($_POST['data'])) {
	    $data = $_POST['data'];
	    if (!isset($_SESSION['results']))
	    	read_data1($data_file);
	    $results = $_SESSION['results'];
	    # прореживание массива данных
	    $results = copy_array($results, $data);
	    $_SESSION['results'] = $results;
	    write_data($filename, $results);
	}
	else {
	    $data = array();
	    $results = read_data1($data_file);
	    if (isset($_SESSION['results'])) unset($_SESSION['results']);
	}

	if (empty($results)) {
	    echo "<h2>Диагноз не установлен</h2>";
	    exit;
	}

	if (($res = check_diagnose($results, $data))) {
		echo "<h3>Подтвержденные симптомы:<h3><p>";
		foreach ($data as $value) {
			list($idx, $val) = explode(",", $value);
			echo "<h2>".$idx." - ";
			echo ($val == 1) ? "да" : "нет";
			echo "</h2><p>";
	   }
	   echo "<h3>Найден диагноз:<h3> <h2>".$res."<h2>";
	}
	else {
		$request = calc_request($results, $data);
		form_request($request, $data);
	}
}

    if (isset($_SESSION['login'])) {
	$flag = (isset($_GET['flag'])) ? $_GET['flag'] : 0;
	if ($flag == 1) {
	    if (isset($_POST['flag']) && $_POST['flag'] == 2) {
		foreach ($_POST['tests'] as $test) {
		    run_tree($test);
		}
	    }
	    else {
		read_parent_data();
	    }
	}
	if ($flag == 3) {
	    foreach ($tests as $test)
		run_tree($data_files."/".$test.".txt");
	}
    }
    else {
        echo "content";
    }
?>
