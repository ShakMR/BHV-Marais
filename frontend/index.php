<?php require 'db/Core/BhvDB.php'; ?>
<?php
	$init = new BhvDB;

	if(isset( $_POST['submit'] )){
		$init_session = new BhvDB;

		try{
			$result = $init_session->authenticate($_POST['username'], $_POST['password']);
			header('location:index.php');
		}
		catch( Exception $e){
			$sms =  $e->getMessage();
			echo '<p class="login-alert">'.$sms.'</p>';
		}
	}
?>

<?php 
	if( $init->check_session() ){
		require 'includes/meta.php';
		include("includes/contest-form.php");
	}else{
		require 'includes/meta.php';
		include("includes/login-form.php");
	}
?>
<?php 
include("includes/footer.php");
?>