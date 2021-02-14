<?php

function getfreekey($array)
{
    $i = 1;

    while (isset($array[$i])) {
	$i += 1;
    }
    return $i;
}

function read_data1($data_file)
{
        global $_SESSION;
        $flag = 0;
        $prev = 0;
        $concat = 0;
        $descr = '';
        $recommends = '';
        $names = array();
        $data = array();
	$buff = '';

        $fp = fopen($data_file, "r") or die("Can't open file ".$data_file);

        if ($fp) {
		$line = 0;
                while (($s = fgets($fp)) !== false) {
			$line++;
			$buff .= $s;
                        $str = trim($s);
                        if (strlen($str) == 0 or $str[0] == '#')
				continue;

			if ($str[0] == "[") {
                		if ($str == "[names]") {
                        		$flag = 1;
                        		continue;
                		}
                		else if ($str == "[diagnoses]") {
                        		$flag = 2;
                        		continue;
                		}
				else if ($str == "[description]") {
                        		$flag = 3;
                        		continue;
				}
                		else if ($str == "[recommends]") {
                			$flag = 4;
                			continue;
                    		}
                    		else {
                        	    $buff .= "<b>Line ".$line.": Unknown identificator ".$str."</b>\n";
                        	    continue;
                    		}
                        }

                        if ($flag == 1) {
                		if ($concat == 1 and $prev > 0) {
                		    $names[$prev] .= $str;
                		}
                		if (substr($str, -1) == '\\') {
                		    ;
                		}
                		$arr = explode(":", $str, 2);
                		if (!is_numeric($arr[0])) {
                        	    $buff .= "<b>Line ".$line.": Unknown format of index ".$arr[0]."</b>\n";
                        	    continue;
                		}
                		if (count($arr) != 2) {
                        	    $buff .= "<b>Line ".$line.": Format string is unknown</b>\n";
                        	    continue;
                		}
                                if (isset($names[$arr[0]])) {
                        	    $buff .= "<b>Line ".$line.": Item ".$arr[0]." already defined</b>\n";
				    continue;
				}
				$prev = $arr[0];
                                $names[$arr[0]] = $arr[1];
                        }

                        if ($flag  == 2) {
                		$diag = explode(":", $str);
                		if (count($diag) != 2) {
                        	    $buff .= "<b>Line ".$line.": Format string is unknown</b>\n";
                        	    continue;
                		}
                		$sost = $diag[0];
                		$prizn = $diag[1];
                                if (isset($data[$sost])) {
                        	    $fk = getfreekey($names);
#                        	    echo $sost." ".$fk."\n";
                        	    $names[$fk] = $names[$sost];
                        	    $sost = $fk;
#				    $buff .= "<b>Line ".$line.": Item already defined</b>\n";
#                                    continue;
                                }
                                if (!isset($names[$sost])) {
                        	    $buff .= "<b>Line ".$line.": Unknown key ".$sost." in names</b>\n";
                                    continue;
                                }
				$data[$sost] = array();
                                $arr = explode(";", $prizn);

                                foreach($arr as $value) {
                                        if ($value) {
                                                $v = explode(",", $value);
        					if (count($v) != 2) {
                				    $buff .= "<b>Line ".$line.": Format string is unknown</b>\n";
                				    continue;
                				}
                        			if (!isset($names[$sost])) {
                        			    $buff .= "<b>Line ".$line.": Unknown key ".$sost." in names</b>\n";
                                		    continue;
                        			}
                				$data[$sost][$v[0]] = $v[1];
                			}
                                }
                        }

                        if ($flag == 3) {
			    if ($descr == '')
				$descr = $str;
			    else
				$descr .= ' '.$str;
                        }
                        
                        if ($flag == 4) {
			    if ($recommends == '')
				$recommends = $str;
			    else
				$recommends .= ' '.$str;
                        }
                }
        }

	$_SESSION['description'] = $descr;
	$_SESSION['recommends'] = $recommends;
	$_SESSION['names'] = $names;
	$_SESSION['results'] = $data;

        fclose($fp);
        return $buff;
}

#$s = read_data1('../data/1.txt');
#echo $s;

?>
