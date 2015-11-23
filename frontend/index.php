<?php require 'db/Core/BhvDB.php'; ?>
<?php $init = new BhvDB; ?>

<?php require 'includes/meta.php'; ?>
<?php 
	session_start();
	if( $init->check_session() ){
		include("includes/contest-form.php");
	}else{
		include("includes/login-form.php");
	}
?>
<?php 
include("includes/footer.php");
?>