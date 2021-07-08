<?php

header("Content-Type: application/json");

require "TempMail.php";
use TempMail\TempMail;

$Email = new TempMail();
if(isset($_GET["do"])){
    if($_GET["do"] == "new"){
        if(isset($_GET["alias"])){
            $create = $Email->CreateAddress($_GET["alias"]);
        }else{
            $create = $Email->CreateAddress();
        }
        print(json_encode($Email->GetAddress(),  JSON_PRETTY_PRINT));
    }elseif($_GET["do"] == "delete"){
        if(!isset($_GET["token"]) || !isset($_GET["email"])){
            print(json_encode(["error" => true, "msg" => "a few arguments are messing."],  JSON_PRETTY_PRINT));
            exit(1);
        }
        $delete = $Email->DeleteAddress($_GET["token"], $_GET["email"]);
        if((bool)$delete === true){
            print(json_encode(["error" => false, "msg" => "email deleted successfully."],  JSON_PRETTY_PRINT));
        }else{
            print(json_encode(["error" => true, "msg" => "error while deleting this address"],  JSON_PRETTY_PRINT));
        }
    }elseif($_GET["do"] == "messages"){
        if(!isset($_GET["token"]) || !isset($_GET["email"])){
            print(json_encode(["error" => true, "msg" => "a few arguments are messing."],  JSON_PRETTY_PRINT));
            exit(1);
        }
        $messages = $Email->Messages($_GET["token"], $_GET["email"]);
        print(json_encode($messages, 128));
    }else{
        print(json_encode(["error" => true, "msg" => "'do' argument not accepted !."],  JSON_PRETTY_PRINT));
    }
}else{
    print(json_encode(["error" => true, "msg" => "Follow Me At : https://github.com/anasybal"],  JSON_PRETTY_PRINT));
}