<?php
define(_CHARSET_,'utf8');
if ($header == '') {
header("Content-Type: text/html; charset="._CHARSET_);
}
function _show_array($array) {
	if (is_array($array)) {
	$return="{<br>";
	foreach ($array as $key => $value) {	
	$return.="<b>$key</b>:"._show_array($value);
	}
		$return.="}<br>";
	} else {
	return $array.";<br>";
	}
	return $return;
}
function _echo($class,$tod,$array) {
	if (is_array($array)) {
	$return="".$_GET['console'].'<br>';
	foreach ($array as $key => $value) {
	$return.="<b>$key</b>:"._show_array($value);
	} return $return;} else {
	return "".$_GET['console']." - $array<br>";
	}
}
session_start();
require_once 'Autoloader.php';
require_once 'config/wallet.php';
include('config/site_config.php');
include('class/db.class.php');
include('class/cache.class.php');
$db=new Db;
$db->connect();
use Autoloader as Autoloader;
list($_GET['class'],$_GET['todo'])=explode(' - ',$_GET['console']);
if ($_GET['class'] == '') {
if ($_GET['class']='\cash');
} else {
	$_GET['class']="\\{$_GET['class']}";
}
$class='wallet'.$_GET['class'];
if (class_exists($class)) {
$obj = new $class;
$_SESSION['console']=_echo($_GET['class'],$GET['todo'],$obj->todo(strtoupper($_GET[todo]))).$_SESSION['console'];
} else {
$_SESSION['console']=_echo($_GET['class'],$GET['todo'],"Нет такой комманды").$_SESSION['console'];
}
?>
<console>
<? echo $_SESSION['console']; ?>
<end id='end'></end>
</console>
<style>
body {
	background:#000;
	overflow:hidden;
}
b {
	color:#006F24;
}
console {
height:calc(90vh);
width:100%;
background:#000;
color:#fff;
display:block;
overflow-y:scroll;
}
input {
	height:calc(7vh);
}
</style>
<form method="get" action="/">
<input type="text" value="<? echo $_GET['console']; ?>" name="console" style='width:90%;'><input type="submit" style='width:10%;'>
</form>