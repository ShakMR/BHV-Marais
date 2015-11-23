<?php require_once('db/Core/BhvDB.php') ?>

<?php
	$init_session = new BhvDB;

	try {
		$result = $init_session->authenticate($_POST['username'], $_POST['password']);
		header('location:index.php');
	}
	catch (Exception $e) {
		echo  $e->getMessage();
	}
?>