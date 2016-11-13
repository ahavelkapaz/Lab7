<?php 

include 'checklogin.php';
include 'db_config.php';

session_start();
$session=session_id();
$time=time();
$date=date("Y-m-d H:i:s");
$time_check=$time-300; // 5 Minutos
$count=0;


$sql="SELECT * FROM users_online WHERE sid='$session'"; 
if($result=mysqli_query($conn,$sql)){
				$count=mysqli_num_rows($result);
			}
			else{echo 'Error mysqli select';}

//Sino esta le añadimos
if($count=="0"){ 
$sql1="INSERT INTO users_online(sid, conn_time,time)VALUES('$session', '$date', '$time')"; 
if($result1=mysqli_query($conn,$sql1)){}
else{echo 'Error insertar' .  mysqli_error($conn);}
}
			

 // si esta,actualizamos el tiempo
 else {
$sql2="UPDATE users_online SET time='$time' WHERE sid = '$session'"; 
$result2=mysqli_query($conn,$sql2); 
}
 // after 5 minutes, session will be deleted 
 $sql4="DELETE FROM users_online WHERE time<$time_check"; 
 $result4=mysqli_query($conn,$sql4); 
 
 $sql3="SELECT * FROM users_online";
 if($result3=mysqli_query($conn,$sql3)){
 $count_user_online=mysqli_num_rows($result3);
 echo "<span class='glyphicon glyphicon-user'></span><b>Usuarios Online : </b>" . $count_user_online;
 }else echo 'Error get count usuarios'; 



 //To see the result run this script in multiple browser. 
mysqli_close($conn);
 ?>
