<?php 
	// Connecttion server
	$servername="localhost";
	$username="root";
	$password="";
	$dbname="tutorial";
	$link=mysqli_connect($servername,$username,$password,$dbname);

	if($connect -> conncet_error){
		die("Bo'glanishda xatolik: ");
	}