<?php require 'db/Core/BhvDB.php'; ?>
<?php $init = new BhvDB; ?>

<?php
	//session_start();
	$b = $init->check_session();
	require 'includes/meta.php';
	if( $b ){
		include("includes/contest-form.php");
	}else{
		include("includes/login-form.php");
	}
?>
<?php 
include("includes/footer.php");
?>