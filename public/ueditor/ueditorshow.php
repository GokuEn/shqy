	<?php 
$con = mysql_connect("localhost","root","GokuEn40870921");
if (!$con)
{
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("shq", $con);
$title = $_POST['title'];
if($title!=""){
	$result = mysql_query("select * from catalog where title = '".$title."'");
	$row =  mysql_fetch_array($result);
	echo $row['text'];
}else{
	$result = mysql_query("select * from catalog limit 1");
	$row =  mysql_fetch_array($result);
	echo $row['text'];
}



mysql_close($con);
?>