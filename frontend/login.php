<?php require_once('db/Core/BhvDB.php') ?>

<?php
	$init_session = new BhvDB;

	$result = $init_session->authenticate($_POST['username'], $_POST['password']);
	
	if($result){
		header('location:index.php');
	}else{
		echo "Usuario Incorrecto";
	}
?>