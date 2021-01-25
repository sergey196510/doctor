<?php

require 'funcs/funcs.php';

function generate_str($len)
{
    $str = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";

    $r = "";
    for ($i = 0; $i < $len; $i++) {
	$r .= $str[rand(0,strlen($str)-1)];
    }

    return $r;
}

if (isset($_GET['em']) and $_GET['em']) {

    include 'view/top.phtml';

    $filename = $_GET['em'];
    if (!file_exists($filename)) {
	echo 'Файл '.$filename.' не существует';
	include 'view/footer.phtml';
	exit;
    }

    $fp = fopen($filename,'r') or die("Can't open file ".$filename);
    $str = fgets($fp, 1000);
    if ($str === false) {
	include 'view/footer.phtml';
	exit;
    }

#    $arr = explode(":",$str);
    fclose($fp);
    unlink($filename);

    $fp = fopen($passwd_file, 'a+') or die("Can't append to file ".$passwd_file);
#    $str = $arr[0].":".$arr[1].":".$arr[2].":".$arr[3].":yyy\n";
    fwrite($fp, $str);
    fclose($fp);

    echo 'Вы успешно зарегистрировались!';

    include 'view/footer.phtml';
    exit;
}
elseif (isset($_GET['login']) and isset($_GET['pass1']) and isset($_GET['pass2']) and isset($_GET['email'])) {

    include 'view/top.phtml';

    if ($_GET['pass1'] != $_GET['pass2']) {
	echo "Пароли не совпадают";
    }
    else {
	$pass = password_hash($_GET['pass1'], PASSWORD_DEFAULT);
#	echo $_GET['pass1'].' '.$pass.'<br>';
	$filename = 'register/'.generate_str(40);
	$fp = fopen($filename, 'w') or die("Can't write to file ".$filename);
	$str = $_GET['login'].":".$pass.":".$_GET['email'].":".$_GET['fullname'];
	fwrite($fp, $str);
	fclose($fp);
#	var_dump($_SERVER);
#	$proto = (isset($_SERVER['HTTPS'])) ? "https:// " : "http://";
	$proto = "http://";
	$to = $_GET['email'];
	$subject = 'Регистрация';
	$message = "Вы отправили запрос на регистрацию.\nНе отвечайте на это письмо.\nЕсли Вы не регистрировались на сайте, игнорируйте письмо.\nДля подтверждения регистрации перейдите по ссылке\n".$proto.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?em=".$filename;
	$headers = array(
	    "From" => "noreply@lserg.ru",
	    "Reply-To" => "noreply@lserg.ru",
	    "X-Mailer" => "PHP/".phpversion()
	);
	if (mail($to, $subject, $message) == false) {
	    echo 'Ошибка при отправке сылки для подтверждения регистрации...';
	}
	else {
	    echo 'На ваш e-mail было отправлено письмо с инструкциями для подтверждения регистраци.';
	}
    }
    include 'view/footer.phtml';
    exit;
}
else {
    generate('register.phtml', 'template.php');
}

?>
