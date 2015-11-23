<?php require 'db/Core/BhvDB.php'; ?>

<?php 
	
	$init = new BhvDB;
    $error = false;
    
    try {
        $ret = $init->new_inscription($_POST['NAME'], $_POST['LAST'], $_POST['MAIL'], $init->today(), $_POST['CODE']);
    } catch (Exception $e) {
        $this->assertTrue($e->getMessage() == "The user is already registered in the system");
        $error = true;
    }
    if (!$error)
        $this->fail();

?>