<?php
/**
 * Created by PhpStorm.
 * User: shakmr
 * Date: 15/11/15
 * Time: 11:59
 */

require_once("Agent.php");
require_once("CodeDispenser.php");
require_once("../Core/BhvDB.php");
require_once("../utils.php");

//echo "Preparing database for unittest\n";
//BhvDB::restore_dump("Dumps/15-11-2015.sql");
//echo "DONE\nStarting UnitTest...\n";

$name_array = ["Pedro", "Lucia", "Marta", "Anna", "Paco", "Manolo", "Victor", "Luis", "Antonio", "Francisco", "Alba", "Alejandro", "Sara", "Raul"];
$last_array = ["Arias", "Berenguer", "Martinez", "More", "Piovan", "Navarro", "Garcia", "Sanchez", "Zapa", "Madri", "Sas", "Guillem", "Plana"];
$agents = array();
$dispenser = new CodeDispenser();
foreach ($name_array as $name) {
    foreach ($last_array as $lastname) {
        foreach ($last_array as $lastname2) {
            $a = new Agent($name, $lastname." ".$lastname2, 0.0077855, $dispenser);
            array_push($agents, $a);
        }
    }
}
shuffle($agents);
//generate timetable
$timetable = [];
$format = "2015-11-";
//november
$agent_index = 0;
$count = 0;
for ($i = 20; $i <= 30; ++$i)
{
    for ($j = 8; $j <= 20; ++$j) {
        $date = $format."$i $j:00:00";
        $timetable[$date] = array();
        for ($k = 0; $k < 5; ++$k) {
            array_push($timetable[$date],$agents[$agent_index++]);
        }
        ++$count;
    }
}
$format = "2015-12-";
for ($i = 01; $i <= 24; ++$i)
{
    for ($j = 8; $j <= 20; ++$j) {
        $date = $format."$i $j:00:00";
        $timetable[$date] = array();
        for ($k = 0; $k < 5; ++$k) {
            array_push($timetable[$date],$agents[$agent_index++]);
        }
        ++$count;
    }
}

$keys = array_keys($timetable);
$exceptions = 0;
$winning = 0;
for ($i = 0; $i < count($timetable); ++$i) {
    $date_key = $keys[$i];
    $date = $timetable[$date_key];
    $datetime = new \DateTime($date_key);
    /**
     * @var Agent $person
     */
    foreach ($date as $person) {
        $data = $person->play();
        if ($person->wants_to_play()) {
            echo $datetime->format('Y-m-d H:i:s') . ": $data[0] $data[1] wants to play\n";
            try {
                $award = BhvDB::new_inscription($data[0], $data[1], $data[2], $datetime->format('Y-m-d H:i:s'), $data[3]);
                if ($award) {

                    echo "$data[2] wins on " . $datetime->format('Y-m-d H:i:s') . "\n";
                }
            } catch (\Exception $e) {
                echo "$data[2] " . $e->getMessage() . "\n";
                $exceptions += 1;
            }
        }
    }
}


//$date = new \DateTime("2015-11-20 08:00:00");
//$datelim = new \DateTime("2015-11-20 20:00:00");
//$dateend = new \DateTime("2015-12-24 20:00:00");
//$intervalHour = new \DateInterval("PT1H");
//$intervalNextDay = new \DateInterval("PT12H");
//$exceptions = 0;
//$winning = 0;
//$array_play = array();
//echo "number of people: ". count($agents)."\n";
///** @var Agent $person */
//while ($date < $dateend) {
//    for ($i = 0; $i < count($agents); ++$i) {
//        $person = $agents[$i];
//        $data = $person->play();
//        if ($person->wants_to_play()) {
//            array_push($array_play, $i);
//            echo $date->format('Y-m-d H:i:s').": $data[0] $data[1] wants to play\n";
//            try {
//                $award = BhvDB::new_inscription($data[0], $data[1], $data[2], $date->format('Y-m-d H:i:s'), $data[3]);
//                if ($award) {
//
//                    echo "$data[2] wins on ".$date->format('Y-m-d H:i:s')."\n";
//                }
//            } catch (\Exception $e) {
//                echo "$data[2] ".$e->getMessage()."\n";
//                $exceptions += 1;
//            }
//        }
////        usleep(500000);
//    }
//    foreach ($array_play as $played) {
//        unset($agents[$played]);
//    }
//    $agents = array_values($agents);
//    echo "number of people: ". count($agents)."\n";
//    $date = $date->add($intervalHour);
//    if ($date > $datelim) {
//        $date = $datelim->add($intervalNextDay);
//    }
//    if (count($agents) <= 0)
//    {
//        $date = $dateend;
//    }
//}
//echo "Number of exceptions: $exceptions\n";
//echo "Number of winners: $winning\n";

