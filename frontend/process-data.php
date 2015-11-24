<?php require 'db/Core/BhvDB.php'; ?>

<?php 
	
	$init = new BhvDB;
    $error = false;
    
    try {
        $ret = $init->new_inscription($_POST['NAME'], $_POST['LAST'], $_POST['MAIL'], $init->today(), $_POST['CODE']);
    } catch (Exception $e) {
        print "<p class='result'>".$e->getMessage()."</p>";
        print "<a class='button-restart' href='index.php'>PRUEBA SUERTE EN EL CONCURSO</a>";
        $error = true;
    }
    if (!$error)
        $this->fail();

?>