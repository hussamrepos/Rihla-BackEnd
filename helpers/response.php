<?php 
function SendResponse ($success,$message,$data){
   echo json_encode([
     "success" => $success,
    "message" => $message,
    "data" => $data
   ]);
}


?>