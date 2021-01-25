<?php

$username = "";
$password = "";
$fullname = "";

if (isset($_POST['auth_name'])) {
    $username = $_POST['auth_name'];
    $password = $_POST['auth_pass'];
    $fullname = $_POST['full_name'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $fp = fopen($passwd_file, "r+") or die("Can't open password file");
    if ($fp) {
	$flag = false;
	while (($str = fgets($fp, 1000)) !== false) {
	    $arr = explode(":", $str);
	    if ($arr[0] == $username) {
		echo "<script>alert(\"Указанный логин существует.\");</script>";
		$flag = true;
	    }
	}
	if ($flag == false) {
	    $str = $username.":".$hash.":".$fullname.":yyy";
	    fwrite($fp, $str);
	    fclose($fp);
	    header("Location: index.php?flag=2");
	    exit;
	}
    }
    else {
	echo "<script>alert(\"Невозможно открыть файл.\");</script>";
    }
}

?>

<div id="comm">
    <form method=POST>
    <center>
    <h3>Новый пользователь</h3>
    <table>
    <tr><td align=right>Имя пользователя:</td><td><input type="text" name="auth_name" value='<?php echo $username; ?>' required></td></tr>
    <tr><td align=right>Пароль:</td><td><input type="text" name="auth_pass" value='<?php echo $password; ?>' required></td></tr>
    <tr><td align=right>Полное имя:</td><td><input type="text" name="full_name" value='<?php echo $fullname; ?>' required></td></tr>
    </table>
    <input type="submit">
    </center>
    </form>
</div>
