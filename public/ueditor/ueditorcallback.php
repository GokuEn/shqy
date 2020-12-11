<?php 
$con = mysql_connect("localhost","root","GokuEn40870921");
if (!$con)
{
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("shq", $con);


$text = $_POST['text'];
$title = $_POST['title'];
$position = $_POST['position'];
if($position==""){
	$position = mysql_num_rows(mysql_query("select id from catalog"));
}
$result = mysql_query("select id from catalog where title = '".$title."'");
if(mysql_num_rows($result)==0){
	mysql_query("update catalog set position = position+1 where position >=".$position);
	mysql_query("insert into catalog (text,title,position) values ('".$text."','".$title."','".$position."')");
}else{
	mysql_query("update catalog set text = '".$text."' where title = '".$title."'");
}


mysql_close($con);
?>