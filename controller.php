<?php 

require_once 'SearchEngineAPI.php';
$s = new SearchEngineAPI();
require_once 'dataClass.php';
$data = new Data();
$sports = $data->sports;

session_start();

if( $_SERVER['REQUEST_URI'] == '/se/tikets/' ){
   
    $id = $_SESSION['tournamentID'];
    $tiketsByEvents = $data->getTikets($id);
    
}
else{
    
    if(isset($_POST['tournaments'])){

        $tournaments;
        $sportEv = $_POST['tournaments'];

        foreach($sports as $key => $value){

            if($value == $sportEv){

                $tournaments = $data->gToutnaments($key);
                $_SESSION['tournaments'] = $tournaments;
                break;
            } 

        }

    }

    if(isset($_POST['getTikets'])){


        $tournament = $_POST['getTikets'];
        $tournaments = $_SESSION['tournaments'];

        foreach($tournaments as $key => $val){

            if($val == $tournament){

                $_SESSION['tournamentID'] = $key;

            }
        }


    }
}

?> 