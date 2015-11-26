<?php require 'db/Core/BhvDB.php'; ?>

<?php 
	
	$init = new BhvDB;
    $error = false;

    try {
        $ret = $init->new_inscription($_POST['NAME'], $_POST['LAST'], $_POST['MAIL'], $init->today(), $_POST['CODE'], $_POST['INFO']);
        if ($ret) {
            print "<p class='result'>" . "ganador" . "</p>";
            print "<a class='button-restart' href='index.php'>GO BACK</a>";
        }
        else {
            print "<p class='result'>" . "better luck next time" . "</p>";
            print "<p class='result-second'> N'hesitez pas à retenter votre chance jusqu'au 24 décembre.</p>";
            print "<a class='button-restart' href='index.php'>REVENIR AU FORMULAIRE</a>";
        }
    } catch (Exception $e) {
        print "<p class='result'>".$e->getMessage()."</p>";
        print "<p class='result-second'> N'hesitez pas à retenter votre chance jusqu'au 24 décembre.</p>";
        print "<a class='button-restart' href='index.php'>REVENIR AU FORMULAIRE</a>";
    }
?>